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
     * @param string $name
     * @param int $age
     * @param string $email
     * @param string $course Course name
     * @param int $year_level
     * @param int $status (0 or 1 for graduation status)
     * @param array $imageFile $_FILES['student_image'] or null
     * @return bool Success status
     */
    public function create($name, $age, $email, $course, $year_level, $status, $imageFile = null)
    {
        // Get course_id from course name
        $course_id = $this->getCourseId($course);
        if (!$course_id) {
            return false;
        }

        // Handle image upload
        $image_path = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
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
     * @param int $id Student ID
     * @param string $name
     * @param int $age
     * @param string $email
     * @param string $course Course name
     * @param int $year_level
     * @param int $status (0 or 1 for graduation status)
     * @param array $imageFile $_FILES['student_image'] or null
     * @return bool Success status
     */
    public function update($id, $name, $age, $email, $course, $year_level, $status, $imageFile = null, $deleteExistingImage = false)
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

        // Determine the final image_path value
        $image_path = $oldStudent['image_path'];  // Default: keep existing

        // Handle image deletion flag
        if ($deleteExistingImage && !empty($oldStudent['image_path'])) {
            $this->deleteImage($oldStudent['image_path']);
            $image_path = null;
        }

        // Handle new image upload
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            // Delete old image if it exists and we're uploading a new one
            if (!empty($oldStudent['image_path'])) {
                $this->deleteImage($oldStudent['image_path']);
            }

            // Upload new image
            $image_path = $this->uploadImage($imageFile);
            if (!$image_path) {
                return false;
            }
        }

        // Update student record with new image_path value
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
            return false; // Student doesn't exist
        }

        // Delete Query
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);

        // Execute delete
        if ($stmt->execute()) {
            $stmt->close();

            // Delete associated image if exists
            if (!empty($student['image_path'])) {
                $this->deleteImage($student['image_path']);
            }

            return true;
        }

        $stmt->close();
        return false;
    }

    /**
     * Get course ID from course name (creates if doesn't exist)
     *
     * @param string $course Course name
     * @return int|null Course ID or null if failed
     */
    private function getCourseId($course)
    {
        // First, try to find existing course
        $sql = "SELECT id FROM courses WHERE course_name = ?";
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

        // If course doesn't exist, create it
        $sql = "INSERT INTO courses (course_name) VALUES (?)";
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
     * Get student record by ID
     *
     * @param int $id Student ID
     * @return array|null Student data or null if not found
     */
    private function getStudentRecord($id)
    {
        $sql = "SELECT * FROM students WHERE id = ?";
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
     * Upload image to uploads directory
     *
     * @param array $file $_FILES['student_image']
     * @return string|null Image path or null if failed
     */
    private function uploadImage($file)
    {
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            return null;
        }

        // Check file size (5MB max)
        $max_size = 5 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            return null;
        }

        // Create uploads directory if it doesn't exist
        $upload_dir = BASE_PATH . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('student_') . '.' . $ext;
        $filepath = $upload_dir . '/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/' . $filename;
        }

        return null;
    }

    /**
     * Delete image from uploads directory
     *
     * @param string $image_path Path to image (relative to BASE_PATH)
     * @return bool Success status
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
