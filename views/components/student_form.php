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
    <input type="hidden" name="student_image_cropped" id="croppedImageData">
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Personal info</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
          <input id="name" name="name" type="text" maxlength="40"
            value="<?= htmlspecialchars($student['name'] ?? '') ?>"
            placeholder="e.g. Maria Santos" required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>
        <div>
          <label for="age" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Age</label>
          <input id="age" name="age" type="number" min="0" max="99"
            value="<?= htmlspecialchars($student['age'] ?? '') ?>"
            placeholder="18" required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>
        <div>
          <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
          <input id="email" name="email" type="email" maxlength="40"
            value="<?= htmlspecialchars($student['email'] ?? '') ?>"
            placeholder="m.santos@school.edu" required
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
          <input id="course" name="course" type="text" maxlength="40"
            value="<?= htmlspecialchars($student['course_name'] ?? $student['course'] ?? '') ?>"
            placeholder="e.g. BS Computer Science" required
            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition">
        </div>
        <div>
          <label for="year_level" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
          <select id="year_level" name="year_level" required
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
            <input type="checkbox" id="grad_status" name="graduation_status" value="1"
              <?= (isset($student['graduation_status']) && $student['graduation_status']) ? 'checked' : '' ?>
              class="w-4 h-4 accent-red-800 cursor-pointer">
            <span class="text-sm text-gray-700">Graduating student</span>
          </label>
        </div>
      </div>
    </div>

    <!-- STUDENT PHOTO -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">Student photo</p>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <!-- STEP 1 — Dropzone -->
      <div id="dropzone"
        class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer transition hover:border-red-800 hover:bg-red-50/30 group <?= ($isEdit && !empty($student['image_path'])) ? 'hidden' : '' ?>">
        <input type="file" name="student_image_raw" accept="image/*" id="fileInput"
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

      <!-- STEP 2 — Crop editor -->
      <div id="cropEditor" class="hidden">
        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
          <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-4">Drag to reposition &middot; Scroll or slide to zoom</p>
          <div id="canvasWrap"
            class="relative mx-auto cursor-grab active:cursor-grabbing select-none overflow-hidden rounded-lg bg-gray-200"
            style="width:300px;height:300px;">
            <canvas id="cropCanvas" width="300" height="300"
              class="block"
              style="width:300px;height:300px;"></canvas>
            <div class="absolute inset-0 pointer-events-none"
              style="border-radius:50%; border:2.5px solid #991b1b; box-sizing: border-box;"></div>
          </div>
          <!-- Zoom controls -->
          <div class="flex items-center gap-3 mt-5">
            <button type="button" id="zoomOut"
              class="w-7 h-7 rounded-full border border-gray-300 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-100 transition flex-shrink-0 text-base leading-none">
              &#8722;
            </button>
            <input type="range" id="zoomSlider" min="100" max="300" value="100" step="1" class="flex-1 accent-red-800">
            <button type="button" id="zoomIn"
              class="w-7 h-7 rounded-full border border-gray-300 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-100 transition flex-shrink-0 text-base leading-none">
              &#43;
            </button>
            <span id="zoomPct" class="text-xs text-gray-400 w-9 text-right flex-shrink-0">100%</span>
          </div>
          <!-- Buttons -->
          <div class="flex gap-3 mt-4">
            <button type="button" id="cropCancelBtn"
              class="flex-1 border border-gray-300 rounded-lg py-2.5 text-sm text-gray-500 bg-white hover:bg-gray-50 transition">
              Cancel
            </button>
            <button type="button" id="cropApplyBtn"
              class="flex-1 bg-red-800 text-white rounded-lg py-2.5 text-sm font-medium hover:bg-red-900 transition">
              Apply crop
            </button>
          </div>
        </div>
      </div>

      <!-- STEP 3 — Preview -->
     <?php
      $showPreview = ($isEdit && !empty($student['image_path']));
      $previewSrc  = $showPreview ? BASE_URL . '/' . htmlspecialchars($student['image_path']) : '';
      $previewName = $showPreview ? basename($student['image_path']) : '';
      ?>
      <div id="preview" class="<?= $showPreview ? '' : 'hidden' ?> mt-3 flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
        <img id="previewImg" src="<?= $previewSrc ?>" alt="preview"
          class="w-12 h-12 rounded-full object-cover border border-gray-200 flex-shrink-0">
        <div class="flex-1 min-w-0">
          <p id="previewName" class="text-sm font-medium text-gray-800 truncate"><?= $previewName ?></p>
        </div>
        <button id="removeFile" type="button"
          class="text-gray-400 hover:text-red-800 transition text-lg px-1 flex-shrink-0 leading-none">
          &times;
        </button>
      </div>
    </div>
    <!-- end student photo -->

    <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
      <button type="button" onclick="history.back()"
        class="border border-gray-300 rounded-lg px-5 py-2.5 text-sm text-gray-500 hover:bg-gray-50 transition">
        Cancel
      </button>
      <button type="submit"
        class="bg-red-800 text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-red-900 transition">
        <?= $isEdit ? 'Update Student' : 'Create Student' ?>
      </button>
    </div>
  </form>
</div>

<script>
(function () {
  const dropzone     = document.getElementById('dropzone');
  const fileInput    = document.getElementById('fileInput');
  const cropEditor   = document.getElementById('cropEditor');
  const canvas       = document.getElementById('cropCanvas');
  const ctx          = canvas.getContext('2d');
  const zoomSlider   = document.getElementById('zoomSlider');
  const zoomPct      = document.getElementById('zoomPct');
  const canvasWrap   = document.getElementById('canvasWrap');
  const zoomInBtn    = document.getElementById('zoomIn');
  const zoomOutBtn   = document.getElementById('zoomOut');
  const cropApplyBtn = document.getElementById('cropApplyBtn');
  const cropCancelBtn= document.getElementById('cropCancelBtn');
  const preview      = document.getElementById('preview');
  const previewImg   = document.getElementById('previewImg');
  const previewName  = document.getElementById('previewName');
  const removeFile   = document.getElementById('removeFile');
  const croppedInput = document.getElementById('croppedImageData');

  // We are working with a 300x300 area to match the crop requirements
  const SIZE = 300;
  const RADIUS = 150;
  const CENTER = 150;

  let img        = new Image();
  let scale      = 1;
  let minScale   = 1;
  let offsetX    = 0;
  let offsetY    = 0;
  let isDragging = false;
  let lastX = 0, lastY = 0;
  let fileName   = 'photo.png';

  /* ── Drawing Logic ── */
  function draw() {
    ctx.clearRect(0, 0, SIZE, SIZE);

    // 1. Draw the user's image with current transformation
    ctx.drawImage(img, offsetX, offsetY, img.naturalWidth * scale, img.naturalHeight * scale);

    // 2. Draw the semi-transparent "mask" outside the circle
    ctx.save();
    ctx.fillStyle = 'rgba(255, 255, 255, 0.55)';
    ctx.beginPath();
    ctx.rect(0, 0, SIZE, SIZE);
    ctx.arc(CENTER, CENTER, RADIUS, 0, Math.PI * 2, true); // Punch the hole
    ctx.fill();
    ctx.restore();
  }

  /* ── Constraints: Keeps the image covering the 300x300 square ── */
  function clampOffset() {
    const w = img.naturalWidth  * scale;
    const h = img.naturalHeight * scale;

    const minX = SIZE - w;
    const minY = SIZE - h;
    const maxX = 0;
    const maxY = 0;

    offsetX = Math.min(maxX, Math.max(minX, offsetX));
    offsetY = Math.min(maxY, Math.max(minY, offsetY));
  }

  function computeMinScale() {
    // Ensures the image is at least 300px on its shortest side
    return Math.max(SIZE / img.naturalWidth, SIZE / img.naturalHeight);
  }

  function sliderToScale(v) {
    return minScale * (1 + ((v - 100) / 100) * 2);
  }

  /* ── Editor Actions ── */
  function openEditor(file) {
    if (!file || !file.type.startsWith('image/')) return;
    fileName = file.name;
    const reader = new FileReader();
    reader.onload = function (e) {
      img.onload = function () {
        minScale = computeMinScale();
        scale    = minScale;

        // Initial center alignment
        offsetX  = (SIZE - (img.naturalWidth * scale)) / 2;
        offsetY  = (SIZE - (img.naturalHeight * scale)) / 2;

        zoomSlider.value    = 100;
        zoomPct.textContent = '100%';
        dropzone.classList.add('hidden');
        preview.classList.add('hidden');
        cropEditor.classList.remove('hidden');
        draw();
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  function applyCrop() {
    // Create the final 300x300 result
    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = SIZE;
    finalCanvas.height = SIZE;
    const fctx = finalCanvas.getContext('2d');

    // Circular clipping path for the final file
    fctx.beginPath();
    fctx.arc(CENTER, CENTER, RADIUS, 0, Math.PI * 2);
    fctx.clip();

    // Draw the image exactly as positioned in the editor
    fctx.drawImage(img, offsetX, offsetY, img.naturalWidth * scale, img.naturalHeight * scale);

    const dataURL = finalCanvas.toDataURL('image/png');
    croppedInput.value      = dataURL;
    previewImg.src          = dataURL;
    previewName.textContent = fileName;

    cropEditor.classList.add('hidden');
    preview.classList.remove('hidden');
  }

  /* ── Interaction Listeners ── */
  fileInput.addEventListener('change', (e) => {
    if (e.target.files[0]) openEditor(e.target.files[0]);
  });

  zoomSlider.addEventListener('input', function () {
    const v = parseInt(this.value);
    zoomPct.textContent = v + '%';

    const oldScale = scale;
    scale = sliderToScale(v);

    // Zoom from center
    const ratio = scale / oldScale;
    offsetX = CENTER - (CENTER - offsetX) * ratio;
    offsetY = CENTER - (CENTER - offsetY) * ratio;

    clampOffset();
    draw();
  });

  // Mouse/Touch Dragging
  const startDrag = (x, y) => {
    isDragging = true;
    lastX = x;
    lastY = y;
  };

  const moveDrag = (x, y) => {
    if (!isDragging) return;
    offsetX += x - lastX;
    offsetY += y - lastY;
    lastX = x;
    lastY = y;
    clampOffset();
    draw();
  };

  canvasWrap.addEventListener('mousedown', (e) => startDrag(e.clientX, e.clientY));
  window.addEventListener('mousemove', (e) => moveDrag(e.clientX, e.clientY));
  window.addEventListener('mouseup', () => isDragging = false);

  canvasWrap.addEventListener('touchstart', (e) => {
    if (e.touches.length === 1) startDrag(e.touches[0].clientX, e.touches[0].clientY);
  }, { passive: true });
  window.addEventListener('touchmove', (e) => {
    if (e.touches.length === 1) moveDrag(e.touches[0].clientX, e.touches[0].clientY);
  }, { passive: true });
  window.addEventListener('touchend', () => isDragging = false);

  // Button Listeners
  cropApplyBtn.addEventListener('click', applyCrop);

  cropCancelBtn.addEventListener('click', () => {
    fileInput.value = '';
    cropEditor.classList.add('hidden');
    dropzone.classList.remove('hidden');
  });

  removeFile.addEventListener('click', () => {
    fileInput.value = '';
    croppedInput.value = '';
    preview.classList.add('hidden');
    dropzone.classList.remove('hidden');
  });

  zoomInBtn.onclick = () => {
    zoomSlider.value = Math.min(300, parseInt(zoomSlider.value) + 10);
    zoomSlider.dispatchEvent(new Event('input'));
  };
  zoomOutBtn.onclick = () => {
    zoomSlider.value = Math.max(100, parseInt(zoomSlider.value) - 10);
    zoomSlider.dispatchEvent(new Event('input'));
  };

})();
</script>
