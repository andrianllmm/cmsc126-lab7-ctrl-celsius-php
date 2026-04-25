<?php

/**
 * ImageHandler
 *
 * Handles all image-related operations for student records:
 * uploading raw files, cropped base64 images, and deleting images.
 */
class ImageHandler
{
    private const FILE_SIZE_LIMIT = 5 * 1024 * 1024; // 5 MB

    /**
     * Save a cropped base64 image to disk
     *
     * Accepts a full data-URL such as:
     *   data:image/png;base64,iVBORw0KGgo...
     *
     * @param string $dataUrl Base64 data-URL
     * @return string|null Relative path (e.g. "student_abc123.png") or null on failure
     */
    public function uploadCroppedImage($dataUrl)
    {
        if (empty($dataUrl)) {
            return null;
        }

        // Strip the data-URL header (e.g. "data:image/png;base64,")
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
            return null;
        }

        $ext = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $allowed = ['png', 'jpg', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            return null;
        }

        $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            return null;
        }

        // Enforce limit
        if (strlen($imageData) > self::FILE_SIZE_LIMIT) {
            return null;
        }

        $filepath = $this->resolveUploadPath('student_' . uniqid() . '.' . $ext);
        if ($filepath && file_put_contents($filepath['absolute'], $imageData) !== false) {
            return $filepath['relative'];
        }

        return null;
    }

    /**
     * Upload a raw image file to the uploads directory
     *
     * @param array $file $_FILES entry
     * @return string|null Relative path or null on failure
     */
    public function uploadImage($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            return null;
        }

        // Enforce limit
        if ($file['size'] > self::FILE_SIZE_LIMIT) {
            return null;
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $this->resolveUploadPath('student_' . uniqid() . '.' . $ext);
        if ($filepath && move_uploaded_file($file['tmp_name'], $filepath['absolute'])) {
            return $filepath['relative'];
        }

        return null;
    }

    /**
     * Delete an image file from the uploads directory
     *
     * @param string $image_path Relative path
     * @return bool
     */
    public function deleteImage($image_path)
    {
        if (empty($image_path)) {
            return false;
        }

        $filepath = BASE_PATH . '/' . $image_path;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }

    /**
     * Ensure the uploads directory exists and return both absolute
     * and relative paths for a given filename.
     */
    private function resolveUploadPath($filename)
    {
        $upload_dir = BASE_PATH . UPLOAD_URL;

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
            return null;
        }

        return [
            'absolute' => $upload_dir . '/' . $filename,
            'relative' => $filename,
        ];
    }
}
