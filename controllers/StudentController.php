<?php

/**
 * StudentController
 *
 * Handles HTTP requests related to student records.
 * Acts as the bridge between the Model (database) and Views (UI).
 */
class StudentController
{
    private $studentModel;

    /**
     * Constructor
     *
     * @param Student $studentModel Instance of Student model
     */
    public function __construct($studentModel)
    {
        $this->studentModel = $studentModel;
    }

    /**
     * Display list of all students
     *
     * Route: GET /
     *
     * @return void
     */
    public function index()
    {
        $students = $this->studentModel->all();

        require BASE_PATH . '/views/students/index.php';
    }

    /**
     * Show create form
     *
     * Route: GET /create
     *
     * @return void
     */
    public function create()
    {
        require BASE_PATH . '/views/students/create.php';
    }

    /**
     * Store new student record
     *
     * Route: POST /store
     *
     * Uses $_POST data from form submission
     *
     * @return void
     */
    public function store()
    {
        $this->studentModel->create(
            $_POST["name"],
            $_POST["age"],
            $_POST["email"],
            $_POST["course"],
            $_POST["year_level"],
            $_POST["status"],
            $_POST["image_path"]
        );

        header("Location: /");
        exit;
    }

    /**
     * Show edit form for a specific student
     *
     * Route: GET /edit/{id}
     *
     * @param int $id Student ID
     * @return void
     */
    public function edit($id)
    {
        $student = $this->studentModel->find($id);

        require BASE_PATH . '/views/students/edit.php';
    }

    /**
     * Update an existing student record
     *
     * Route: POST /update/{id}
     *
     * @param int $id Student ID
     * @return void
     */
    public function update($id)
    {
        $this->studentModel->update(
            $id,
            $_POST["name"],
            $_POST["age"],
            $_POST["email"],
            $_POST["course"],
            $_POST["year_level"],
            $_POST["status"],
            $_POST["image_path"]
        );

        header("Location: /");
        exit;
    }

    /**
     * Delete a student record
     *
     * Route: GET /delete/{id}
     *
     * @param int $id Student ID
     * @return void
     */
    public function delete($id)
    {
        $this->studentModel->delete($id);

        header("Location: /");
        exit;
    }
}
