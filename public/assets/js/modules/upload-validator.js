document.getElementById("studentForm").addEventListener("submit", function (e) {
  const croppedData = document.getElementById("croppedImageData").value.trim();

  const fileInput = document.getElementById("fileInput");
  const hasRawFile = fileInput && fileInput.files && fileInput.files.length > 0;

  const previewHidden = document
    .getElementById("preview")
    .classList.contains("hidden");

  const photoError = document.getElementById("photoError");
  const dropzone = document.getElementById("dropzone");

  // Valid if:
  // - cropped image exists OR
  // - raw file selected OR
  // - existing image preview is visible (edit mode)
  const hasPhoto = croppedData !== "" || hasRawFile || !previewHidden;

  if (!hasPhoto) {
    e.preventDefault();
    photoError.classList.remove("hidden");

    dropzone.classList.add("border-red-600");
    dropzone.classList.remove("border-gray-300");

    dropzone.scrollIntoView({ behavior: "smooth", block: "center" });
  } else {
    photoError.classList.add("hidden");
    dropzone.classList.remove("border-red-600");
    dropzone.classList.add("border-gray-300");
  }
});

// Clear error when user picks a file
document.getElementById("fileInput").addEventListener("change", function () {
  document.getElementById("photoError").classList.add("hidden");

  const dropzone = document.getElementById("dropzone");
  dropzone.classList.remove("border-red-600");
  dropzone.classList.add("border-gray-300");
});
