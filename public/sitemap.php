<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = rtrim(defined('APP_URL') ? APP_URL : '/', '/');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= htmlspecialchars($baseUrl . '/', ENT_XML1, 'UTF-8') ?></loc>
    </url>
</urlset>