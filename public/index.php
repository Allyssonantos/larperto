<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Helper.php';

$route = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');

if ($route === '') {
    require __DIR__ . '/../app/views/home/index.php';
    exit;
}

http_response_code(404);
echo 'Página não encontrada.';