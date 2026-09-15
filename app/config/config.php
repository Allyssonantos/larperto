<?php
/**
 * Configurações gerais do site LarPerto
 */

// Caminhos
define('ROOT_PATH', dirname(__DIR__, 2));          // Pasta raiz do projeto
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads/imoveis');

// Caminho público relativo: funciona no localhost e também pelo IP da rede.
define('BASE_URL', '/larperto/public');

// Configurações do site
define('SITE_NAME', 'LarPerto');
define('SITE_DESCRIPTION', 'Imóveis para venda e aluguel em Posse-GO e região');

// Upload
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024);  // 5 MB
define('MAX_IMAGES_PER_PROPERTY', 15);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Sessão
define('SESSION_NAME', 'larperto_session');

// Ambiente
define('ENVIRONMENT', 'development');  // development | production

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Exibir erros (somente em development)
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}