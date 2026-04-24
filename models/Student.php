<?php

/**
 * Student Model
 *
 * Handles all database operations related to students.
 * Uses MySQLi for database interaction.
 *
 * Tables:
 * - students
 * - courses (linked via course_id)
 */

class Student
{
    private $conn;

    /**
     * Constructor
     *
     * @param mysqli $db Database connection instance
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get all students with their course names
     *
     * @return array List of students
     */
    public function all()
    {
        $sql = "
            SELECT
                students.*,
                courses.course_name
            FROM students
            JOIN courses ON students.course_id = courses.id
        ";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Find a student by ID
     *
     * Retrieves student data along with course name from courses table
     *
     * @param int $id Student ID
     * @return array|null Student data or null if not found
     */
    public function find($id)
    {
        $sql = "
            SELECT
                students.*,
                courses.course_name
            FROM students
            LEFT JOIN courses ON students.course_id = courses.id
            WHERE students.id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $stmt->close();
            return $student;
        }

        $stmt->close();
        return null;
    }

    /**
     * Search students by keyword (name or email)
     *
     * @param string $keyword Search term
     * @return array Matching students
     */
    public function search($keyword) {}

    /**
     * Create a new student record
     *
     * Accepts either a cropped base64 image (from the crop editor) or a
     * raw $_FILES upload. The cropped image takes priority.
     *
     * @param string $name
     * @param int    $age
     * @param string $email
     * @param string $course       Course name
     * @param int    $year_level
     * @param int    $status       0 or 1 for graduation status
     * @param array  $imageFile    $_FILES['student_image_raw'] or null
     * @param string $croppedImage Base64 data-URL from the crop editor or ''
     * @return bool Success status
     */
    public function create($name, $age, $email, $course, $year_level, $status, $imageFile = null, $croppedImage = '')
    {
        // Get course_id from course name
        $course_id = $this->getCourseId($course);
        if (!$course_id) {
            return false;
        }

        // Handle image — cropped base64 takes priority over raw file upload
        $image_path = null;
        if (!empty($croppedImage)) {
            $image_path = $this->saveCroppedImage($croppedImage);
            if (!$image_path) {
                return false;
            }
        } elseif ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $image_path = $this->uploadImage($imageFile);
            if (!$image_path) {
                return false;
            }
        }

        // Convert status checkbox to boolean (1 or 0)
        $status = $status ? 1 : 0;

        // Prepare and execute insert statement
        $sql = "
            INSERT INTO students (name, age, email, course_id, year_level, graduation_status, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sisiiis", $name, $age, $email, $course_id, $year_level, $status, $image_path);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    /**
     * Update an existing student record
     *
     * Accepts either a cropped base64 image (from the crop editor) or a
     * raw $_FILES upload. The cropped image takes priority.
     *
     * @param int    $id
     * @param string $name
     * @param int    $age
     * @param string $email
     * @param string $course              Course name
     * @param int    $year_level
     * @param int    $status              0 or 1 for graduation status
     * @param array  $imageFile           $_FILES['student_image_raw'] or null
     * @param bool   $deleteExistingImage Whether to remove the current image
     * @param string $croppedImage        Base64 data-URL from the crop editor or ''
     * @return bool Success status
     */
    public function update($id, $name, $age, $email, $course, $year_level, $status, $imageFile = null, $deleteExistingImage = false, $croppedImage = '')
    {
        // Get course_id from course name
        $course_id = $this->getCourseId($course);
        if (!$course_id) {
            return false;
        }

        // Convert status checkbox to boolean (1 or 0)
        $status = $status ? 1 : 0;

        // Get current student record
        $oldStudent = $this->getStudentRecord($id);
        if (!$oldStudent) {
            return false;
        }

        // Start with the existing image path
        $image_path = $oldStudent['image_path'];

        // Handle explicit image deletion flag
        if ($deleteExistingImage && !empty($oldStudent['image_path'])) {
            $this->deleteImage($oldStudent['image_path']);
            $image_path = null;
        }

        // Cropped base64 takes priority over raw file upload
        if (!empty($croppedImage)) {
            if (!empty($oldStudent['image_path'])) {
                $this->deleteImage($oldStudent['image_path']);
            }
            $image_path = $this->saveCroppedImage($croppedImage);
            if (!$image_path) {
                return false;
            }
        } elseif ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            if (!empty($oldStudent['image_path'])) {
                $this->deleteImage($oldStudent['image_path']);
            }
            $image_path = $this->uploadImage($imageFile);
            if (!$image_path) {
                return false;
            }
        }

        // Update student record
        $sql = "
            UPDATE students
            SET name = ?, age = ?, email = ?, course_id = ?, year_level = ?, graduation_status = ?, image_path = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sisiiisi", $name, $age, $email, $course_id, $year_level, $status, $image_path, $id);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    /**
     * Delete a student record by ID
     *
     * @param int $id
     * @return bool Success status
     */
    public function delete($id)
    {
        $student = $this->getStudentRecord($id);

        if (!$student) {
            return false;
        }

        $sql  = "DELETE FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();

            if (!empty($student['image_path'])) {
                $this->deleteImage($student['image_path']);
            }

            return true;
        }

        $stmt->close();
        return false;
    }

    /**
     * Save a cropped base64 image (from the front-end crop editor) to disk
     *
     * Accepts a full data-URL such as:
     *   data:image/png;base64,iVBORw0KGgo...
     *
     * @param string $dataUrl Base64 data-URL
     * @return string|null Relative path (e.g. "uploads/student_abc123.png") or null on failure
     */
    private function saveCroppedImage($dataUrl)
    {
        if (empty($dataUrl)) {
            return null;
        }

        // Strip the data-URL header (e.g. "data:image/png;base64,")
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
            return null;
        }

        $ext     = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $allowed = ['png', 'jpg', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            return null;
        }

        $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
        $imageData  = base64_decode($base64Data);

        if ($imageData === false) {
            return null;
        }

        // Enforce 5 MB limit
        if (strlen($imageData) > 5 * 1024 * 1024) {
            return null;
        }

        // Ensure uploads directory exists
        $upload_dir = BASE_PATH . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $filename = 'student_' . uniqid() . '.' . $ext;
        $filepath = $upload_dir . '/' . $filename;

        if (file_put_contents($filepath, $imageData) !== false) {
            return 'uploads/' . $filename;
        }

        return null;
    }

    /**
     * Get course ID from course name (creates the course if it doesn't exist)
     *
     * @param string $course Course name
     * @return int|null Course ID or null if failed
     */
    private function getCourseId($course)
    {
        $sql  = "SELECT id FROM courses WHERE course_name = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $course);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['id'];
        }

        $stmt->close();

        // Course doesn't exist — create it
        $sql  = "INSERT INTO courses (course_name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $course);

        if ($stmt->execute()) {
            $course_id = $this->conn->insert_id;
            $stmt->close();
            return $course_id;
        }

        $stmt->close();
        return null;
    }

    /**
     * Get a raw student record by ID (no course join)
     *
     * @param int $id Student ID
     * @return array|null
     */
    private function getStudentRecord($id)
    {
        $sql  = "SELECT * FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        }

        $stmt->close();
        return null;
    }

    /**
     * Upload a raw image file to the uploads directory
     *
     * @param array $file $_FILES entry
     * @return string|null Relative path or null on failure
     */
    private function uploadImage($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            return null;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $upload_dir = BASE_PATH . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'student_' . uniqid() . '.' . $ext;
        $filepath = $upload_dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/' . $filename;
        }

        return null;
    }

    /**
     * Delete an image file from the uploads directory
     *
     * @param string $image_path Relative path (e.g. "uploads/student_abc.png")
     * @return bool
     */
    private function deleteImage($image_path)
    {
        if (empty($image_path)) {
            return false;
        }

        $filepath = BASE_PATH . '/' . $image_path;

        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }
}
