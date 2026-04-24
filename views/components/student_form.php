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

    <!-- Hidden input that carries the cropped base64 PNG to the server -->
    <input type="hidden" name="student_image_cropped" id="croppedImageData">

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

    <!-- ============================================================
         STUDENT PHOTO — with drag-to-pan & zoom crop editor
         ============================================================ -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Student photo</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <!-- STEP 1 — Dropzone (hidden when editing with an existing image) -->
      <div
        id="dropzone"
        class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer transition hover:border-red-800 hover:bg-red-50/30 group <?= ($isEdit && !empty($student['image_path'])) ? 'hidden' : '' ?>">
        <input
          type="file"
          name="student_image_raw"
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

      <!-- STEP 2 — Crop editor (shown after a file is selected) -->
      <div id="cropEditor" class="hidden">
        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
          <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-4">Drag to reposition &middot; Scroll or slide to zoom</p>

          <!-- Canvas + circular ring overlay -->
          <div
            id="canvasWrap"
            class="relative mx-auto cursor-grab active:cursor-grabbing select-none"
            style="width:280px;height:280px;">
            <canvas
              id="cropCanvas"
              width="280"
              height="280"
              class="block rounded-full border-2 border-gray-200"
              style="width:280px;height:280px;"></canvas>
            <!-- Red ring drawn on top of the canvas -->
            <div class="absolute inset-0 rounded-full pointer-events-none" style="border:2.5px solid #991b1b;"></div>
          </div>

          <!-- Zoom controls -->
          <div class="flex items-center gap-3 mt-5">
            <button
              type="button"
              id="zoomOut"
              class="w-7 h-7 rounded-full border border-gray-300 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-100 transition flex-shrink-0 text-base leading-none">
              &#8722;
            </button>
            <input
              type="range"
              id="zoomSlider"
              min="100"
              max="300"
              value="100"
              step="1"
              class="flex-1 accent-red-800">
            <button
              type="button"
              id="zoomIn"
              class="w-7 h-7 rounded-full border border-gray-300 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-100 transition flex-shrink-0 text-base leading-none">
              &#43;
            </button>
            <span id="zoomPct" class="text-xs text-gray-400 w-9 text-right flex-shrink-0">100%</span>
          </div>

          <!-- Editor action buttons -->
          <div class="flex gap-3 mt-4">
            <button
              type="button"
              id="cropCancelBtn"
              class="flex-1 border border-gray-300 rounded-lg py-2.5 text-sm text-gray-500 bg-white hover:bg-gray-50 transition">
              Cancel
            </button>
            <button
              type="button"
              id="cropApplyBtn"
              class="flex-1 bg-red-800 text-white rounded-lg py-2.5 text-sm font-medium hover:bg-red-900 transition">
              Apply crop
            </button>
          </div>
        </div>
      </div>

      <!-- STEP 3 — Preview (shown after crop is applied, or when editing existing image) -->
      <?php
      $showPreview = false;
      $previewSrc  = '';
      $previewName = '';
      $previewSize = '';

      if ($isEdit && !empty($student['image_path'])) {
          $showPreview = true;
          $previewSrc  = BASE_URL . '/' . htmlspecialchars($student['image_path']);
          $previewName = basename($student['image_path']);
          $previewSize = 'Existing photo';
      }
      ?>

      <div id="preview" class="<?= $showPreview ? '' : 'hidden' ?> mt-3 flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
        <img
          id="previewImg"
          src="<?= $previewSrc ?>"
          alt="preview"
          class="w-12 h-12 rounded-full object-cover border border-gray-200 flex-shrink-0">
        <div class="flex-1 min-w-0">
          <p id="previewName" class="text-sm font-medium text-gray-800 truncate"><?= $previewName ?></p>
          <p id="previewSize" class="text-xs text-gray-400"><?= $previewSize ?></p>
        </div>
        <button
          id="removeFile"
          type="button"
          class="text-gray-400 hover:text-red-800 transition text-lg px-1 flex-shrink-0 leading-none">
          &times;
        </button>
      </div>

    </div>
    <!-- end student photo -->

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
(function () {
  /* ── Element refs ────────────────────────────────────────────── */
  const dropzone        = document.getElementById('dropzone');
  const fileInput       = document.getElementById('fileInput');
  const cropEditor      = document.getElementById('cropEditor');
  const canvas          = document.getElementById('cropCanvas');
  const ctx             = canvas.getContext('2d');
  const zoomSlider      = document.getElementById('zoomSlider');
  const zoomPct         = document.getElementById('zoomPct');
  const canvasWrap      = document.getElementById('canvasWrap');
  const zoomInBtn       = document.getElementById('zoomIn');
  const zoomOutBtn      = document.getElementById('zoomOut');
  const cropApplyBtn    = document.getElementById('cropApplyBtn');
  const cropCancelBtn   = document.getElementById('cropCancelBtn');
  const preview         = document.getElementById('preview');
  const previewImg      = document.getElementById('previewImg');
  const previewName     = document.getElementById('previewName');
  const previewSize     = document.getElementById('previewSize');
  const removeFile      = document.getElementById('removeFile');
  const croppedInput    = document.getElementById('croppedImageData');

  const SIZE = 280; // canvas dimensions (px)

  /* ── State ───────────────────────────────────────────────────── */
  let img      = new Image();
  let scale    = 1;
  let minScale = 1;
  let offsetX  = 0;
  let offsetY  = 0;
  let isDragging = false;
  let lastX = 0, lastY = 0;
  let fileName = 'photo.jpg';

  /* ── Draw ────────────────────────────────────────────────────── */
  function draw() {
    ctx.clearRect(0, 0, SIZE, SIZE);
    ctx.save();
    // Clip to circle
    ctx.beginPath();
    ctx.arc(SIZE / 2, SIZE / 2, SIZE / 2, 0, Math.PI * 2);
    ctx.clip();
    ctx.drawImage(img, offsetX, offsetY, img.naturalWidth * scale, img.naturalHeight * scale);
    ctx.restore();
  }

  /* ── Clamp offset so image always fills the circle ───────────── */
  function clampOffset() {
    const w = img.naturalWidth  * scale;
    const h = img.naturalHeight * scale;
    offsetX = Math.min(0, Math.max(SIZE - w, offsetX));
    offsetY = Math.min(0, Math.max(SIZE - h, offsetY));
  }

  /* ── Compute minimum scale to cover the canvas ───────────────── */
  function computeMinScale() {
    return Math.max(SIZE / img.naturalWidth, SIZE / img.naturalHeight);
  }

  /* ── Convert slider value (100–300) → actual scale ──────────── */
  function sliderToScale(v) {
    return minScale * (1 + ((v - 100) / 100) * 2);
  }

  /* ── Load image into editor ──────────────────────────────────── */
  function openEditor(file) {
    if (!file || !file.type.startsWith('image/')) return;
    fileName = file.name;

    const reader = new FileReader();
    reader.onload = function (e) {
      img.onload = function () {
        minScale = computeMinScale();
        scale    = minScale;
        const w  = img.naturalWidth  * scale;
        const h  = img.naturalHeight * scale;
        offsetX  = (SIZE - w) / 2;
        offsetY  = (SIZE - h) / 2;

        // Reset slider
        zoomSlider.value   = 100;
        zoomPct.textContent = '100%';

        // Show editor, hide others
        dropzone.classList.add('hidden');
        preview.classList.add('hidden');
        cropEditor.classList.remove('hidden');

        draw();
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  /* ── File input change ───────────────────────────────────────── */
  fileInput.addEventListener('change', function (e) {
    if (e.target.files[0]) openEditor(e.target.files[0]);
  });

  /* ── Drag-and-drop onto dropzone ─────────────────────────────── */
  dropzone.addEventListener('dragover', function (e) {
    e.preventDefault();
    dropzone.classList.add('!border-red-800', 'bg-red-50/30');
  });
  dropzone.addEventListener('dragleave', function () {
    dropzone.classList.remove('!border-red-800', 'bg-red-50/30');
  });
  dropzone.addEventListener('drop', function (e) {
    e.preventDefault();
    dropzone.classList.remove('!border-red-800', 'bg-red-50/30');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) openEditor(file);
  });

  /* ── Zoom slider ─────────────────────────────────────────────── */
  zoomSlider.addEventListener('input', function () {
    const v       = parseInt(zoomSlider.value);
    zoomPct.textContent = v + '%';

    // Zoom toward center of canvas
    const cx       = SIZE / 2 - offsetX;
    const cy       = SIZE / 2 - offsetY;
    const newScale = sliderToScale(v);
    const ratio    = newScale / scale;

    offsetX = SIZE / 2 - cx * ratio;
    offsetY = SIZE / 2 - cy * ratio;
    scale   = newScale;

    clampOffset();
    draw();
  });

  zoomInBtn.addEventListener('click', function () {
    const v = Math.min(300, parseInt(zoomSlider.value) + 10);
    zoomSlider.value = v;
    zoomSlider.dispatchEvent(new Event('input'));
  });
  zoomOutBtn.addEventListener('click', function () {
    const v = Math.max(100, parseInt(zoomSlider.value) - 10);
    zoomSlider.value = v;
    zoomSlider.dispatchEvent(new Event('input'));
  });

  /* ── Scroll-to-zoom on canvas ────────────────────────────────── */
  canvas.addEventListener('wheel', function (e) {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -5 : 5;
    const v     = Math.min(300, Math.max(100, parseInt(zoomSlider.value) + delta));
    zoomSlider.value = v;
    zoomSlider.dispatchEvent(new Event('input'));
  }, { passive: false });

  /* ── Mouse drag ──────────────────────────────────────────────── */
  canvasWrap.addEventListener('mousedown', function (e) {
    isDragging = true;
    lastX = e.clientX;
    lastY = e.clientY;
  });
  window.addEventListener('mousemove', function (e) {
    if (!isDragging) return;
    offsetX += e.clientX - lastX;
    offsetY += e.clientY - lastY;
    lastX    = e.clientX;
    lastY    = e.clientY;
    clampOffset();
    draw();
  });
  window.addEventListener('mouseup', function () { isDragging = false; });

  /* ── Touch drag ──────────────────────────────────────────────── */
  canvasWrap.addEventListener('touchstart', function (e) {
    if (e.touches.length === 1) {
      isDragging = true;
      lastX = e.touches[0].clientX;
      lastY = e.touches[0].clientY;
    }
  }, { passive: true });
  window.addEventListener('touchmove', function (e) {
    if (!isDragging || e.touches.length !== 1) return;
    offsetX += e.touches[0].clientX - lastX;
    offsetY += e.touches[0].clientY - lastY;
    lastX    = e.touches[0].clientX;
    lastY    = e.touches[0].clientY;
    clampOffset();
    draw();
  }, { passive: true });
  window.addEventListener('touchend', function () { isDragging = false; });

  /* ── Apply crop ──────────────────────────────────────────────── */
  cropApplyBtn.addEventListener('click', function () {
    const dataURL = canvas.toDataURL('image/png');

    // Store in hidden input for form submission
    croppedInput.value = dataURL;

    // Show preview thumbnail
    previewImg.src             = dataURL;
    previewName.textContent    = fileName;
    previewSize.textContent    = 'Cropped · ready to upload';

    cropEditor.classList.add('hidden');
    preview.classList.remove('hidden');
  });

  /* ── Cancel crop ─────────────────────────────────────────────── */
  cropCancelBtn.addEventListener('click', function () {
    fileInput.value    = '';
    croppedInput.value = '';
    cropEditor.classList.add('hidden');
    dropzone.classList.remove('hidden');
  });

  /* ── Remove chosen photo ─────────────────────────────────────── */
  removeFile.addEventListener('click', function () {
    fileInput.value    = '';
    croppedInput.value = '';
    ctx.clearRect(0, 0, SIZE, SIZE);
    preview.classList.add('hidden');
    cropEditor.classList.add('hidden');
    dropzone.classList.remove('hidden');
  });
})();
</script>
