<?php

declare(strict_types=1);

final class Upload
{
    public static function saveMany(array $files, int $propertyId): void
    {
        if (!isset($files['name']) || !is_array($files['name'])) {
            return;
        }
        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        $db = Database::getInstance()->getConnection();
        $count = 0;
        foreach ($files['name'] as $index => $name) {
            if ($count >= MAX_IMAGES_PER_PROPERTY || ($files['error'][$index] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }
            $tmp = $files['tmp_name'][$index] ?? '';
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
            $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!is_uploaded_file($tmp) || ($files['size'][$index] ?? 0) > MAX_IMAGE_SIZE || !isset($extensions[$mime])) {
                continue;
            }
            $filename = bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
            $target = UPLOAD_PATH . '/' . $filename;
            if (move_uploaded_file($tmp, $target)) {
                $statement = $db->prepare('INSERT INTO imovel_imagens (imovel_id, arquivo, principal, ordem) VALUES (?, ?, ?, ?)');
                $statement->execute([$propertyId, $filename, $count === 0 ? 1 : 0, $count]);
                $count++;
            }
        }
    }

    public static function isValid(array $file): bool
    {
        return isset($file['error'], $file['tmp_name'])
            && $file['error'] === UPLOAD_ERR_OK
            && is_uploaded_file($file['tmp_name']);
    }
}