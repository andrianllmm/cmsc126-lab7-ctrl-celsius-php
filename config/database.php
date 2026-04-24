<?php

class Database
{
    private $host;
    private $user;
    private $pass;
    private $name;

    public $conn;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->pass = getenv('DB_PASS') ?: '';
        $this->name = getenv('DB_NAME') ?: 'cmsc126_db';
    }

    public function connect()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->name
        );

        if ($this->conn->connect_error) {
            throw new Exception("Database connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
