CREATE DATABASE IF NOT EXISTS cmsc126_db;
USE cmsc126_db;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(40) NOT NULL UNIQUE
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2) NOT NULL,
    email VARCHAR(40) NOT NULL,
    course_id INT NOT NULL,
    year_level INT(1) NOT NULL,
    graduation_status BOOLEAN NOT NULL DEFAULT 0,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
