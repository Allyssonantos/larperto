<?php

declare(strict_types=1);

final class Upload
{
    public static function isValid(array $file): bool
    {
        return isset($file['error'], $file['tmp_name'])
            && $file['error'] === UPLOAD_ERR_OK
            && is_uploaded_file($file['tmp_name']);
    }
}