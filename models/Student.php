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
            JOIN courses ON students.course_id = courses.id
            WHERE students.id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
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
     * @param int $course_id
     * @param int $year_level
     * @param bool $status
     * @param string $image_path
     * @return bool Success status
     */
    public function create($name, $age, $email, $course, $year_level, $status, $image_path) {
        // Get course_id from course name
        $course_id = $this->getCourseId($course);
        if (!$course_id) {
            return false;
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
     * @param int $id
     * @param string $name
     * @param int $age
     * @param string $email
     * @param int $course_id
     * @param int $year_level
     * @param bool $status
     * @param string $image_path
     * @return bool Success status
     */
    public function update($id, $name, $age, $email, $course, $year_level, $status, $image_path) {}

    /**
     * Delete a student record by ID
     *
     * @param int $id
     * @return bool Success status
     */
    public function delete($id) {}
}
