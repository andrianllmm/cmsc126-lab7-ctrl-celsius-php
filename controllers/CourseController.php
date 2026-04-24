<?php

class CourseController
{
    private $courseModel;

    public function __construct($courseModel)
    {
        $this->courseModel = $courseModel;
    }

    public function index()
    {
        $courses = $this->courseModel->all();
        require BASE_PATH . '/views/courses/index.php';
    }
}
