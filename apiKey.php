<?php
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("Environment file not found: " . $filePath);
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        putenv("$name=$value");
    }
}

loadEnv(__DIR__ . '/apiKeys.env');