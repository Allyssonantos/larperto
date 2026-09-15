<?php
/**
 * Front Controller - LarPerto
 */

// Carrega as configurações primeiro
require_once dirname(__DIR__) . '/app/config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helper.php';

// Inicia a sessão
session_name(SESSION_NAME);
session_start();

// Pega a URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);

// Roteamento simples (MVP)
$routes = [
    ''                  => 'home',
    'entrar'            => 'auth/login',
    'cadastrar'         => 'auth/register',
    'sair'              => 'auth/logout',
    'minha-conta'       => 'usuario/dashboard',
    'anunciar'          => 'usuario/criar-anuncio',
];

// Verifica se a rota existe
if (array_key_exists($url, $routes)) {
    $page = $routes[$url];
} else {
    // Rota dinâmica de imóvel: imovel/slug-do-imovel
    if (preg_match('/^imovel\/([a-z0-9-]+)$/', $url, $matches)) {
        $page = 'imovel/visualizar';
        $slug = $matches[1];
    } else {
        $page = '404';
    }
}

// Caminho da view
$viewFile = APP_PATH . '/views/' . $page . '.php';

if (file_exists($viewFile)) {
    require_once APP_PATH . '/views/layouts/header.php';
    require_once $viewFile;
    require_once APP_PATH . '/views/layouts/footer.php';
} else {
    http_response_code(404);
    echo "<h1>Página não encontrada</h1>";
    echo "<p><a href='" . Helper::url() . "'>Voltar para o início</a></p>";
}