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
     * Search students by keyword (student_id, name, or email)
     *
     * @param string $keyword Search term
     * @return array Matching students
     */
    public function search($keyword)
    {
        $sql = "
            SELECT
                students.*,
                courses.course_name
            FROM students
            JOIN courses ON students.course_id = courses.id
            WHERE students.student_id LIKE ?
            OR students.name LIKE ?
            OR students.email LIKE ?
            ORDER BY students.name
        ";

        $stmt = $this->conn->prepare($sql);
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param("sss", $likeKeyword, $likeKeyword, $likeKeyword);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

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
    public function create($name, $age, $email, $course, $year_level, $status, $image_path) {}

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
