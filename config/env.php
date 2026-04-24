<?php

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception(".env file not found: " . $filePath);
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        if (str_contains($value, '#')) {
            $value = trim(explode('#', $value)[0]);
        }

        $value = trim($value, "\"'");

        if ($key === '') {
            continue;
        }

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
