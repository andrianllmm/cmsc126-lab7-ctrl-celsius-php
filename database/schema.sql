CREATE DATABASE IF NOT EXISTS cmsc126_db;
USE cmsc126_db;

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS student_id_seq;

-- Sequence counter table
CREATE TABLE student_id_seq (
    next_val INT NOT NULL DEFAULT 1
);
INSERT INTO student_id_seq VALUES (1);

CREATE TABLE courses (
    id          INT         AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(40) NOT NULL UNIQUE
);

CREATE TABLE students (
    id                INT          AUTO_INCREMENT PRIMARY KEY,
    student_id        VARCHAR(15)  UNIQUE,
    name              VARCHAR(40)  NOT NULL,
    age               INT          NOT NULL,
    email             VARCHAR(40)  NOT NULL,
    course_id         INT          NOT NULL,
    year_level        INT          NOT NULL,
    graduation_status BOOLEAN      NOT NULL DEFAULT 0,
    image_path        VARCHAR(255),
    created_at        TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);