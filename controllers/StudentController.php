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
    private $courseModel;

    /**
     * Constructor
     *
     * @param Student $studentModel Instance of Student model
     * @param Course  $courseModel  Instance of Course model
     */
    public function __construct($studentModel, $courseModel)
    {
        $this->studentModel = $studentModel;
        $this->courseModel  = $courseModel;
    }

    /**
     * Display list of all students or search results
     *
     * Route: GET /
     *
     * @return void
     */
    public function index()
    {
        $query = $_GET['q'] ?? '';
        if (!empty($query)) {
            $students = $this->studentModel->search($query);
        } else {
            $students = $this->studentModel->all();
        }

        require BASE_PATH . '/views/students/index.php';
    }

    /**
     * Show a single student
     *
     * Route: GET /student/{id}
     */
    public function show($id)
    {
        $student = $this->studentModel->find($id);

        if (!$student) {
            http_response_code(404);
            require BASE_PATH . '/views/errors/404.php';
            return;
        }

        require BASE_PATH . '/views/students/show.php';
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
        $courses = $this->courseModel->all();

        require BASE_PATH . '/views/students/create.php';
    }

    /**
     * Store new student record
     *
     * Route: POST /store
     *
     * Reads the cropped base64 image from $_POST['student_image_cropped']
     * (set by the front-end crop editor). Falls back to a raw file upload
     * in $_FILES['student_image_raw'] if no cropped data is present.
     *
     * @return void
     */
    public function store()
    {
        $imageFile    = isset($_FILES['student_image_raw']) ? $_FILES['student_image_raw'] : null;
        $croppedImage = isset($_POST['student_image_cropped']) ? trim($_POST['student_image_cropped']) : '';

        $this->studentModel->create(
            $_POST['name'],
            $_POST['age'],
            $_POST['email'],
            $_POST['course'],
            $_POST['year_level'],
            isset($_POST['graduation_status']) ? 1 : 0,
            $imageFile,
            $croppedImage
        );

        header('Location: ' . (BASE_URL ?: '/'));
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
        $courses = $this->courseModel->all();

        require BASE_PATH . '/views/students/edit.php';
    }

    /**
     * Update an existing student record
     *
     * Route: POST /update/{id}
     *
     * Reads the cropped base64 image from $_POST['student_image_cropped']
     * (set by the front-end crop editor). Falls back to a raw file upload
     * in $_FILES['student_image_raw'] if no cropped data is present.
     *
     * @param int $id Student ID
     * @return void
     */
    public function update($id)
    {
        $imageFile           = isset($_FILES['student_image_raw']) ? $_FILES['student_image_raw'] : null;
        $deleteExistingImage = isset($_POST['delete_image']) && $_POST['delete_image'] == '1';
        $croppedImage        = isset($_POST['student_image_cropped']) ? trim($_POST['student_image_cropped']) : '';

        $this->studentModel->update(
            $id,
            $_POST['name'],
            $_POST['age'],
            $_POST['email'],
            $_POST['course'],
            $_POST['year_level'],
            isset($_POST['graduation_status']) ? 1 : 0,
            $imageFile,
            $deleteExistingImage,
            $croppedImage
        );

        header('Location: ' . (BASE_URL ?: '/'));
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

        header('Location: ' . (BASE_URL ?: '/'));
        exit;
    }
}
