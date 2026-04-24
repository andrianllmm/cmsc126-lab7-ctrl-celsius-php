<?php
$student = $student ?? null;
$isEdit = isset($student);
?>

<div class="w-full">

  <div class="mb-6">
    <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">
      <?= $isEdit ? 'Edit record' : 'New enrollment' ?>
    </p>
    <h1 class="text-2xl font-semibold text-gray-800">Student Record</h1>
  </div>

  <form method="POST"
    action="<?= BASE_URL . ($isEdit ? '/update/' . $student['id'] : '/store') ?>"
    enctype="multipart/form-data"
    class="space-y-8">

    <!-- Personal Info -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Personal info</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
          <input
            id="name"
            name="name"
            type="text"
            maxlength="40"
            value="<?= htmlspecialchars($student['name'] ?? '') ?>"
            placeholder="e.g. Maria Santos"
            required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>

        <div>
          <label for="age" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Age</label>
          <input
            id="age"
            name="age"
            type="number"
            min="0"
            max="99"
            value="<?= htmlspecialchars($student['age'] ?? '') ?>"
            placeholder="18"
            required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>

        <div>
          <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
          <input
            id="email"
            name="email"
            type="email"
            maxlength="40"
            value="<?= htmlspecialchars($student['email'] ?? '') ?>"
            placeholder="m.santos@school.edu"
            required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>
      </div>
    </div>

    <!-- Academic Details -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Academic details</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="course" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Course</label>
          <input
            id="course"
            name="course"
            type="text"
            maxlength="40"
            value="<?= htmlspecialchars($student['course_name'] ?? $student['course'] ?? '') ?>"
            placeholder="e.g. BS Computer Science"
            required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>

        <div>
          <label for="year_level" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
          <select
            id="year_level"
            name="year_level"
            required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition appearance-none bg-white">
            <option value="">Select year</option>
            <?php for ($i = 1; $i <= 4; $i++): ?>
              <option value="<?= $i ?>" <?= (isset($student['year_level']) && $student['year_level'] == $i) ? 'selected' : '' ?>>
                Year <?= $i ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition">
            <input
              type="checkbox"
              id="grad_status"
              name="graduation_status"
              value="1"
              <?= (isset($student['graduation_status']) && $student['graduation_status']) ? 'checked' : '' ?>
              class="w-4 h-4 accent-red-800 cursor-pointer">
            <span class="text-sm text-gray-700">Graduating student</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Student Photo -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Student photo</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <!-- Drop Zone -->
      <div
        id="dropzone"
        class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer transition hover:border-red-800 hover:bg-red-50/30 group">
        <input
          type="file"
          name="student_image"
          accept="image/*"
          id="fileInput"
          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        <div class="flex flex-col items-center gap-2 pointer-events-none">
          <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mb-1 group-hover:bg-red-100 transition">
            <svg class="w-5 h-5 text-red-800" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 3v10M6 7l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M3 14v1a2 2 0 002 2h10a2 2 0 002-2v-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Drop image here</p>
          <p class="text-xs text-gray-400">or <span class="text-red-800 font-medium">browse to upload</span> &middot; PNG, JPG up to 5MB</p>
        </div>
      </div>

      <!-- File Preview -->
      <div id="preview" class="hidden mt-3 flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
        <img id="previewImg" src="" alt="preview" class="w-12 h-12 rounded-lg object-cover border border-gray-200 flex-shrink-0">
        <div class="flex-1 min-w-0">
          <p id="previewName" class="text-sm font-medium text-gray-800 truncate"></p>
          <p id="previewSize" class="text-xs text-gray-400"></p>
        </div>
        <button id="removeFile" type="button" class="text-gray-400 hover:text-red-800 transition text-lg px-1 flex-shrink-0 leading-none">&times;</button>
      </div>

      <?php if ($isEdit && !empty($student['image_path'])): ?>
        <p class="text-xs text-gray-400 mt-2">Current file: <?= htmlspecialchars($student['image_path']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
      <button
        type="button"
        onclick="history.back()"
        class="border border-gray-300 rounded-lg px-5 py-2.5 text-sm text-gray-500 hover:bg-gray-50 transition">
        Cancel
      </button>
      <button
        type="submit"
        class="bg-red-800 text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-red-900 transition">
        <?= $isEdit ? 'Update Student' : 'Create Student' ?>
      </button>
    </div>

  </form>
</div>

<script>
  const dropzone    = document.getElementById('dropzone');
  const fileInput   = document.getElementById('fileInput');
  const preview     = document.getElementById('preview');
  const previewImg  = document.getElementById('previewImg');
  const previewName = document.getElementById('previewName');
  const previewSize = document.getElementById('previewSize');
  const removeFile  = document.getElementById('removeFile');

  function showPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) { previewImg.src = e.target.result; };
    reader.readAsDataURL(file);
    previewName.textContent = file.name;
    const kb = file.size / 1024;
    previewSize.textContent = kb > 1024
      ? (kb / 1024).toFixed(1) + ' MB'
      : Math.round(kb) + ' KB';
    preview.classList.remove('hidden');
    dropzone.classList.add('hidden');
  }

  fileInput.addEventListener('change', function(e) {
    if (e.target.files[0]) showPreview(e.target.files[0]);
  });

  removeFile.addEventListener('click', function() {
    fileInput.value = '';
    preview.classList.add('hidden');
    dropzone.classList.remove('hidden');
  });

  dropzone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropzone.classList.add('!border-red-800', 'bg-red-50/30');
  });

  dropzone.addEventListener('dragleave', function() {
    dropzone.classList.remove('!border-red-800', 'bg-red-50/30');
  });

  dropzone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropzone.classList.remove('!border-red-800', 'bg-red-50/30');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      try {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
      } catch(err) {}
      showPreview(file);
    }
  });
</script>
