<?php

// Ensure student is defined (null if creating new)
$student = $student ?? null;

// Determine if we're editing an existing record
$isEdit = isset($student);

// Set form action depending on mode
$formAction = BASE_URL . ($isEdit ? '/update/' . $student['id'] : '/store');

// The course_id of the current student
$selectedCourseId = $student['course_id'] ?? null;

// Determine if an image preview should be shown
$previewVisible = $isEdit && !empty($student['image_path']);

// Build preview image source and filename safely
$previewSrc = $previewVisible ? UPLOAD_URL . '/' . htmlspecialchars($student['image_path']) : '';
$previewName = $previewVisible ? basename($student['image_path']) : '';
?>

<?php require_once BASE_PATH . '/helpers/form_field.php'; ?>

<div class="w-full">
  <form id="studentForm" method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" class="space-y-8">

    <!-- Hidden field to store cropped image -->
    <input type="hidden" name="student_image_cropped" id="croppedImageData">

    <!-- Personal -->
    <div class="flex items-center gap-3 mb-4">
      <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Personal</p>
      <div class="flex-1 h-px bg-gray-200"></div>
    </div>

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

    <!-- Academic -->
    <div class="flex items-center gap-3 mb-4">
      <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Academic</p>
      <div class="flex-1 h-px bg-gray-200"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Course -->
      <div>
        <label for="course" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
          Course <span class="text-red-600">*</span>
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

      <!-- Year Level -->
      <div>
        <label for="year_level" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
          Year Level <span class="text-red-600">*</span>
        </label>

        <select id="year_level" name="year_level" required
          class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800
                       focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition
                       appearance-none bg-white">

          <option value="">Select year</option>

          <?php for ($i = 1; $i <= 4; $i++): ?>
            <option value="<?= $i ?>"
              <?= isset($student['year_level']) && (int)$student['year_level'] === $i ? 'selected' : '' ?>>
              Year <?= $i ?>
            </option>
          <?php endfor; ?>

        </select>
      </div>

      <!-- Graduation Status -->
      <div class="md:col-span-2">
        <label class="flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition">
          <input type="checkbox" id="grad_status" name="graduation_status" value="1"
            <?= !empty($student['graduation_status']) ? 'checked' : '' ?>
            class="w-4 h-4 accent-red-800 cursor-pointer">

          <span class="text-sm text-gray-700">Graduating this year?</span>
        </label>
      </div>
    </div>

    <!-- Photo -->
    <div class="flex items-center gap-3 mb-4">
      <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Photo</p>
      <div class="flex-1 h-px bg-gray-200"></div>
    </div>

    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider -mt-6 mb-2">
      UPLOAD <span class="text-red-600">*</span>
    </p>

    <p id="photoError" class="hidden text-xs text-red-600 font-medium -mt-2 mb-2">
      A student photo is required.
    </p>

    <!-- File dropzone -->
    <div id="dropzone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer transition hover:border-red-800 hover:bg-red-50/30 group
        <?= $previewVisible ? 'hidden' : '' ?>">

      <input type="file" name="student_image_raw" accept="image/*" id="fileInput"
        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

      <div class="flex flex-col items-center gap-2 pointer-events-none">
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mb-1 group-hover:bg-red-100 transition">
          <i class="fa-solid fa-image fa-xs text-red-800"></i>
        </div>
        <p class="text-sm font-medium text-gray-700">Drop image here</p>
        <p class="text-xs text-gray-400">
          or <span class="text-red-800 font-medium">browse to upload</span>
          · PNG, JPG up to 5MB
        </p>
      </div>
    </div>

    <!-- Crop editor -->
    <?php require_once BASE_PATH . '/views/components/crop_editor.php'; ?>

    <!-- Preview -->
    <div id="preview"
      class="<?= $previewVisible ? '' : 'hidden' ?>
                mt-3 flex items-center gap-3 px-4 py-3 bg-gray-50 border rounded-lg">

      <img id="previewImg" src="<?= $previewSrc ?>" alt="preview"
        class="w-12 h-12 rounded-full object-cover border">

      <div class="flex-1 min-w-0">
        <p id="previewName" class="text-sm font-medium truncate">
          <?= $previewName ?>
        </p>
      </div>

      <button id="removeFile" type="button"
        class="text-gray-400 hover:text-red-800 text-lg">
        &times;
      </button>
    </div>

    <!-- Action buttons -->
    <div class="pt-4 border-t flex items-center justify-between">
      <button type="button" onclick="history.back()"
        class="border rounded-lg px-5 py-2.5 text-sm">
        Cancel
      </button>

      <button type="submit"
        class="bg-red-800 text-white rounded-lg px-6 py-2.5 text-sm">
        <?= $isEdit ? 'Update' : 'Create' ?>
      </button>
    </div>
  </form>
</div>

<script src="<?= BASE_URL ?>/public/assets/js/modules/photo-uploader.js"></script>
<script src="<?= BASE_URL ?>/public/assets/js/modules/upload-validator.js"></script>
