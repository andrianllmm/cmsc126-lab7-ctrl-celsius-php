document.getElementById("studentForm").addEventListener("submit", function (e) {
  const croppedData = document.getElementById("croppedImageData").value.trim();
  const previewHidden = document
    .getElementById("preview")
    .classList.contains("hidden");
  const photoError = document.getElementById("photoError");
  const dropzone = document.getElementById("dropzone");

  // A photo is considered present if:
  //   • a cropped base64 blob exists in the hidden field, OR
  //   • the preview panel is visible (edit mode with an existing image intact)
  const hasPhoto = croppedData !== "" || !previewHidden;

  if (!hasPhoto) {
    e.preventDefault();
    photoError.classList.remove("hidden");

    // Highlight the dropzone border to draw attention
    dropzone.classList.add("border-red-600");
    dropzone.classList.remove("border-gray-300");

    // Scroll the dropzone into view smoothly
    dropzone.scrollIntoView({ behavior: "smooth", block: "center" });
  } else {
    photoError.classList.add("hidden");
    dropzone.classList.remove("border-red-600");
    dropzone.classList.add("border-gray-300");
  }
});

// Clear the error once a file is picked or a crop is confirmed
document.getElementById("fileInput").addEventListener("change", function () {
  document.getElementById("photoError").classList.add("hidden");
  const dropzone = document.getElementById("dropzone");
  dropzone.classList.remove("border-red-600");
  dropzone.classList.add("border-gray-300");
});
