<?php

function asset_upload($file)
{
    $file = $file ?: 'placeholder.jpg';

    $path = BASE_PATH . '/public/assets/uploads/' . $file;

    if (!file_exists($path)) {
        $file = 'placeholder.jpg';
    }

    return UPLOAD_URL . '/' . $file;
}
