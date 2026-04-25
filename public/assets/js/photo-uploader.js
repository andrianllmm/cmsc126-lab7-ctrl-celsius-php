/**
 * StudentPhotoUploader
 * Handles the 3-step photo flow: dropzone → crop editor → preview.
 */
class StudentPhotoUploader {
  static SIZE = 300;
  static RADIUS = 150;
  static CENTER = 150;

  constructor() {
    // DOM refs
    this.dropzone = document.getElementById("dropzone");
    this.fileInput = document.getElementById("fileInput");
    this.cropEditor = document.getElementById("cropEditor");
    this.canvas = document.getElementById("cropCanvas");
    this.ctx = this.canvas.getContext("2d");
    this.canvasWrap = document.getElementById("canvasWrap");
    this.zoomSlider = document.getElementById("zoomSlider");
    this.zoomPct = document.getElementById("zoomPct");
    this.preview = document.getElementById("preview");
    this.previewImg = document.getElementById("previewImg");
    this.previewName = document.getElementById("previewName");
    this.croppedInput = document.getElementById("croppedImageData");

    // State
    this.img = new Image();
    this.scale = 1;
    this.minScale = 1;
    this.offsetX = 0;
    this.offsetY = 0;
    this.fileName = "photo.png";
    this.isDragging = false;
    this.lastX = 0;
    this.lastY = 0;

    this._bindEvents();
  }

  /* ─── Drawing ───────────────────────────────────────────── */

  draw() {
    const { ctx, img, offsetX, offsetY, scale } = this;
    const { SIZE, RADIUS, CENTER } = StudentPhotoUploader;

    ctx.clearRect(0, 0, SIZE, SIZE);
    ctx.drawImage(
      img,
      offsetX,
      offsetY,
      img.naturalWidth * scale,
      img.naturalHeight * scale,
    );

    // Darken area outside the crop circle
    ctx.save();
    ctx.fillStyle = "rgba(255,255,255,0.55)";
    ctx.beginPath();
    ctx.rect(0, 0, SIZE, SIZE);
    ctx.arc(CENTER, CENTER, RADIUS, 0, Math.PI * 2, true);
    ctx.fill();
    ctx.restore();
  }

  /* ─── Transform helpers ──────────────────────────────────── */

  clampOffset() {
    const { SIZE } = StudentPhotoUploader;
    const w = this.img.naturalWidth * this.scale;
    const h = this.img.naturalHeight * this.scale;
    this.offsetX = Math.min(0, Math.max(SIZE - w, this.offsetX));
    this.offsetY = Math.min(0, Math.max(SIZE - h, this.offsetY));
  }

  computeMinScale() {
    const { SIZE } = StudentPhotoUploader;
    return Math.max(
      SIZE / this.img.naturalWidth,
      SIZE / this.img.naturalHeight,
    );
  }

  /** Maps the 100–300 slider range to a meaningful scale multiplier. */
  sliderToScale(sliderValue) {
    return this.minScale * (1 + ((sliderValue - 100) / 100) * 2);
  }

  /* ─── Steps ──────────────────────────────────────────────── */

  openEditor(file) {
    if (!file?.type.startsWith("image/")) return;

    this.fileName = file.name;
    const reader = new FileReader();

    reader.onload = (e) => {
      this.img.onload = () => {
        const { SIZE } = StudentPhotoUploader;
        this.minScale = this.computeMinScale();
        this.scale = this.minScale;
        this.offsetX = (SIZE - this.img.naturalWidth * this.scale) / 2;
        this.offsetY = (SIZE - this.img.naturalHeight * this.scale) / 2;
        this.zoomSlider.value = 100;
        this.zoomPct.textContent = "100%";
        this._showStep("editor");
        this.draw();
      };
      this.img.src = e.target.result;
    };

    reader.readAsDataURL(file);
  }

  applyCrop() {
    const { SIZE, RADIUS, CENTER } = StudentPhotoUploader;
    const out = document.createElement("canvas");
    out.width = SIZE;
    out.height = SIZE;
    const octx = out.getContext("2d");

    octx.beginPath();
    octx.arc(CENTER, CENTER, RADIUS, 0, Math.PI * 2);
    octx.clip();
    octx.drawImage(
      this.img,
      this.offsetX,
      this.offsetY,
      this.img.naturalWidth * this.scale,
      this.img.naturalHeight * this.scale,
    );

    const dataURL = out.toDataURL("image/png");
    this.croppedInput.value = dataURL;
    this.previewImg.src = dataURL;
    this.previewName.textContent = this.fileName;
    this._showStep("preview");
  }

  reset() {
    this.fileInput.value = "";
    this.croppedInput.value = "";
    this._showStep("dropzone");
  }

  adjustZoom(delta) {
    this.zoomSlider.value = Math.min(
      300,
      Math.max(100, +this.zoomSlider.value + delta),
    );
    this.zoomSlider.dispatchEvent(new Event("input"));
  }

  /* ─── Private ────────────────────────────────────────────── */

  _showStep(step) {
    this.dropzone.classList.toggle("hidden", step !== "dropzone");
    this.cropEditor.classList.toggle("hidden", step !== "editor");
    this.preview.classList.toggle("hidden", step !== "preview");
  }

  _onZoom(sliderValue) {
    const v = parseInt(sliderValue, 10);
    const { CENTER } = StudentPhotoUploader;
    const oldScale = this.scale;
    this.scale = this.sliderToScale(v);
    const ratio = this.scale / oldScale;
    this.offsetX = CENTER - (CENTER - this.offsetX) * ratio;
    this.offsetY = CENTER - (CENTER - this.offsetY) * ratio;
    this.clampOffset();
    this.zoomPct.textContent = v + "%";
    this.draw();
  }

  _startDrag(x, y) {
    this.isDragging = true;
    this.lastX = x;
    this.lastY = y;
  }
  _endDrag() {
    this.isDragging = false;
  }

  _moveDrag(x, y) {
    if (!this.isDragging) return;
    this.offsetX += x - this.lastX;
    this.offsetY += y - this.lastY;
    this.lastX = x;
    this.lastY = y;
    this.clampOffset();
    this.draw();
  }

  _bindEvents() {
    const u = this;

    u.fileInput.addEventListener("change", (e) =>
      u.openEditor(e.target.files[0]),
    );
    u.zoomSlider.addEventListener("input", (e) => u._onZoom(e.target.value));

    document
      .getElementById("cropApplyBtn")
      .addEventListener("click", () => u.applyCrop());
    document
      .getElementById("cropCancelBtn")
      .addEventListener("click", () => u.reset());
    document
      .getElementById("removeFile")
      .addEventListener("click", () => u.reset());
    document
      .getElementById("zoomIn")
      .addEventListener("click", () => u.adjustZoom(+10));
    document
      .getElementById("zoomOut")
      .addEventListener("click", () => u.adjustZoom(-10));

    // Mouse drag
    u.canvasWrap.addEventListener("mousedown", (e) =>
      u._startDrag(e.clientX, e.clientY),
    );
    window.addEventListener("mousemove", (e) =>
      u._moveDrag(e.clientX, e.clientY),
    );
    window.addEventListener("mouseup", () => u._endDrag());

    // Touch drag
    u.canvasWrap.addEventListener(
      "touchstart",
      (e) => {
        if (e.touches.length === 1)
          u._startDrag(e.touches[0].clientX, e.touches[0].clientY);
      },
      { passive: true },
    );
    window.addEventListener(
      "touchmove",
      (e) => {
        if (e.touches.length === 1)
          u._moveDrag(e.touches[0].clientX, e.touches[0].clientY);
      },
      { passive: true },
    );
    window.addEventListener("touchend", () => u._endDrag());
  }
}

document.addEventListener("DOMContentLoaded", () => new StudentPhotoUploader());
