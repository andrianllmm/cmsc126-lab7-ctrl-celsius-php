<?php
/**
 * StudentRepository
 *
 * Handles low-level database lookups shared across the Student model:
 * fetching raw student records and resolving / creating course IDs.
 */
class StudentRepository
{
    private $conn;

    /**
     * @param mysqli $db Database connection instance
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get a raw student record by ID (no course join)
     *
     * @param int $id Student ID
     * @return array|null
     */
    public function getStudentRecord($id)
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
     * Get course ID from course name.
     * Creates the course record automatically if it does not yet exist.
     *
     * @param string $course Course name
     * @return int|null Course ID or null on failure
     */
    public function getCourseId($course)
    {
        // Try to find the existing course first
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
}
