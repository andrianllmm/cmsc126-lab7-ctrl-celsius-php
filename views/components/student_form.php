<?php

// Ensure $student is defined (null if creating new)
$student = $student ?? null;

// Determine if we're editing an existing record
$isEdit = isset($student);

// Set form action depending on mode (create vs update)
$formAction = BASE_URL . ($isEdit ? '/update/' . $student['id'] : '/store');

// Determine if an image preview should be shown
$previewVisible = $isEdit && !empty($student['image_path']);

// Build preview image source and filename safely
$previewSrc = $previewVisible ? BASE_URL . '/public/assets/uploads/' . htmlspecialchars($student['image_path']) : '';
$previewName = $previewVisible ? basename($student['image_path']) : '';


// The course_id of the current student (for pre-selecting in edit mode)
$selectedCourseId = $student['course_id'] ?? null;
?>

<!-- Import reusable form helper functions -->
<?php require_once BASE_PATH . '/helpers/form_helper.php'; ?>


<!-- -----------------------------
     STUDENT FORM COMPONENT
     Used for both CREATE and EDIT
-------------------------------- -->
<div class="w-full">

  <!-- Header -->
  <header class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Student Record</h1>
  </header>

  <!-- Main Form -->
  <form method="POST" action="<?= $formAction ?>"
        enctype="multipart/form-data" class="space-y-8">

    <!-- Hidden field to store cropped image (base64 or blob data) -->
    <input type="hidden" name="student_image_cropped" id="croppedImageData">


    <?= sectionDivider('Personal info') ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- Full Name -->
      <div class="md:col-span-2">
        <?= formField('name', 'Full Name', [
            'type'        => 'text',
            'maxlength'   => 40,
            'value'       => $student['name'] ?? '',
            'placeholder' => 'e.g. Juan Dela Cruz',
        ]) ?>
      </div>

      <!-- Age -->
      <div>
        <?= formField('age', 'Age', [
            'type'        => 'number',
            'min'         => 0,
            'max'         => 99,
            'value'       => $student['age'] ?? '',
            'placeholder' => '18',
        ]) ?>
      </div>

      <!-- Email -->
      <div>
        <?= formField('email', 'Email Address', [
            'type'        => 'email',
            'maxlength'   => 40,
            'value'       => $student['email'] ?? '',
            'placeholder' => 'jdcruz@up.edu.ph',
        ]) ?>
      </div>
    </div>

    <!-- =============================
         ACADEMIC DETAILS SECTION
    ============================== -->
    <?= sectionDivider('Academic details') ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- Course Dropdown -->
      <div>
        <label for="course" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
          Course
        </label>

        <select id="course" name="course" required
                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800
                       focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition
                       appearance-none bg-white">

          <option value="">Select a course</option>

          <?php foreach ($courses as $course): ?>
            <option value="<?= htmlspecialchars($course['id']) ?>"
                    <?= $selectedCourseId == $course['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($course['course_name']) ?>
            </option>
          <?php endforeach; ?>

        </select>
      </div>

      <!-- Year Level Dropdown -->
      <div>
        <label for="year_level" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
          Year Level
        </label>

        <select id="year_level" name="year_level" required
                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800
                       focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition
                       appearance-none bg-white">

          <option value="">Select year</option>

          <!-- Generate Year 1–4 dynamically -->
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <option value="<?= $i ?>" <?= yearSelected($student, $i) ?>>
              Year <?= $i ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <!-- Graduation Status Checkbox -->
      <div class="md:col-span-2">
        <label class="flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition">

          <input type="checkbox" id="grad_status" name="graduation_status" value="1"
                 <?= !empty($student['graduation_status']) ? 'checked' : '' ?>
                 class="w-4 h-4 accent-red-800 cursor-pointer">

          <span class="text-sm text-gray-700">Graduating this year?</span>
        </label>
      </div>
    </div>

    <!-- =============================
         STUDENT PHOTO SECTION
    ============================== -->
    <?= sectionDivider('Student photo') ?>

    <!-- FILE DROPZONE (shown only when no preview exists) -->
    <div id="dropzone"
         class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer
                transition hover:border-red-800 hover:bg-red-50/30 group
                <?= $previewVisible ? 'hidden' : '' ?>">

      <!-- Hidden file input -->
      <input type="file" name="student_image_raw" accept="image/*" id="fileInput"
             class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

      <!-- UI Content -->
      <div class="flex flex-col items-center gap-2 pointer-events-none">
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mb-1 group-hover:bg-red-100 transition">
          <!-- Upload Icon -->
          <svg class="w-5 h-5 text-red-800" viewBox="0 0 20 20" fill="none">
            <path d="M10 3v10M6 7l4-4 4 4" stroke="currentColor" stroke-width="1.5"/>
            <path d="M3 14v1a2 2 0 002 2h10a2 2 0 002-2v-1" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </div>

        <p class="text-sm font-medium text-gray-700">Drop image here</p>
        <p class="text-xs text-gray-400">
          or <span class="text-red-800 font-medium">browse to upload</span>
          · PNG, JPG up to 5MB
        </p>
      </div>
    </div>

    <!-- =============================
               CROP EDITOR
    ============================== -->
    <?php require_once BASE_PATH . '/views/components/crop_editor.php'; ?>

    <!-- =============================
         IMAGE PREVIEW SECTION
    ============================== -->
    <div id="preview"
         class="<?= $previewVisible ? '' : 'hidden' ?>
                mt-3 flex items-center gap-3 px-4 py-3 bg-gray-50 border rounded-lg">

      <!-- Profile Thumbnail -->
      <img id="previewImg" src="<?= $previewSrc ?>" alt="preview"
           class="w-12 h-12 rounded-full object-cover border">

      <!-- File Name -->
      <div class="flex-1 min-w-0">
        <p id="previewName" class="text-sm font-medium truncate">
          <?= $previewName ?>
        </p>
      </div>

      <!-- Remove Button -->
      <button id="removeFile" type="button"
              class="text-gray-400 hover:text-red-800 text-lg">
        &times;
      </button>
    </div>

    <!-- =============================
         FORM ACTION BUTTONS
    ============================== -->
    <div class="pt-4 border-t flex items-center justify-between">

      <!-- Cancel Button -->
      <button type="button" onclick="history.back()"
              class="border rounded-lg px-5 py-2.5 text-sm">
        Cancel
      </button>

      <!-- Submit Button -->
      <button type="submit"
              class="bg-red-800 text-white rounded-lg px-6 py-2.5 text-sm">
        <?= $isEdit ? 'Update Student' : 'Create Student' ?>
      </button>
    </div>

  </form>
</div>

<!-- External JS for image upload + crop logic -->
<script src="<?= BASE_URL ?>/public/assets/js/photo-uploader.js"></script>
