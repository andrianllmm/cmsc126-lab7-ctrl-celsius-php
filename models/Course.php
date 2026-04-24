<?php

class Course
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all()
    {
        $sql = "SELECT * FROM courses";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
