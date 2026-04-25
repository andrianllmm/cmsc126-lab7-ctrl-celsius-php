<?php
require_once BASE_PATH . '/helpers/ImageHandler.php';
require_once BASE_PATH . '/models/StudentRepository.php';

/**
 * Student Model
 *
 * Handles all database operations related to students.
 * Delegates image handling to ImageHandler and shared DB queries to StudentRepository.
 *
 * Tables:
 * - students
 * - courses (linked via course_id)
 */
class Student
{
    private $conn;
    private $imageHandler;
    private $repository;

    /**
     * @param mysqli $db Database connection instance
     */
    public function __construct($db)
    {
        $this->conn         = $db;
        $this->imageHandler = new ImageHandler();
        $this->repository   = new StudentRepository($db);
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
     * Retrieves student data along with course name from courses table.
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
        $course_id = $this->repository->getCourseId($course);
        if (!$course_id) {
            return false;
        }

        // Handle image — cropped base64 takes priority over raw file upload
        $image_path = null;
        if (!empty($croppedImage)) {
            $image_path = $this->imageHandler->saveCroppedImage($croppedImage);
            if (!$image_path) {
                return false;
            }
        } elseif ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $image_path = $this->imageHandler->uploadImage($imageFile);
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
        $course_id = $this->repository->getCourseId($course);
        if (!$course_id) {
            return false;
        }

        // Convert status checkbox to boolean (1 or 0)
        $status = $status ? 1 : 0;

        // Get current student record
        $oldStudent = $this->repository->getStudentRecord($id);
        if (!$oldStudent) {
            return false;
        }

        // Start with the existing image path
        $image_path = $oldStudent['image_path'];

        // Handle explicit image deletion flag
        if ($deleteExistingImage && !empty($oldStudent['image_path'])) {
            $this->imageHandler->deleteImage($oldStudent['image_path']);
            $image_path = null;
        }

        // Cropped base64 takes priority over raw file upload
        if (!empty($croppedImage)) {
            if (!empty($oldStudent['image_path'])) {
                $this->imageHandler->deleteImage($oldStudent['image_path']);
            }
            $image_path = $this->imageHandler->saveCroppedImage($croppedImage);
            if (!$image_path) {
                return false;
            }
        } elseif ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            if (!empty($oldStudent['image_path'])) {
                $this->imageHandler->deleteImage($oldStudent['image_path']);
            }
            $image_path = $this->imageHandler->uploadImage($imageFile);
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
        $student = $this->repository->getStudentRecord($id);
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
                $this->imageHandler->deleteImage($student['image_path']);
            }
            return true;
        }

        $stmt->close();
        return false;
    }
}
