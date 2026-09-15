<?php

require_once dirname(__DIR__) . '/app/config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helper.php';
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/Upload.php';

session_name(SESSION_NAME);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$db = Database::getInstance()->getConnection();
$url = trim((string) ($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = APP_PATH . '/views/' . $view . '.php';
    if (!is_file($viewFile)) {
        http_response_code(404);
        echo 'Página não encontrada.';
        return;
    }
    require APP_PATH . '/views/layouts/header.php';
    require $viewFile;
    require APP_PATH . '/views/layouts/footer.php';
}

function requireLogin(): void
{
    if (!Auth::check()) {
        Helper::setFlash('error', 'Entre na sua conta para continuar.');
        Helper::redirect(Helper::url('entrar'));
    }
}

function propertyFilters(PDO $db): array
{
    $where = ["i.status = 'publicado'"];
    $params = [];
    $fields = ['finalidade', 'categoria_id', 'cidade_id'];
    foreach ($fields as $field) {
        if (!empty($_GET[$field])) {
            $where[] = "i.$field = :$field";
            $params[$field] = (int) $_GET[$field];
        }
    }
    if (!empty($_GET['bairro'])) {
        $where[] = 'i.bairro LIKE :bairro';
        $params['bairro'] = '%' . trim($_GET['bairro']) . '%';
    }
    foreach (['minimo' => '>=', 'maximo' => '<='] as $input => $operator) {
        if ($_GET[$input] ?? '' !== '') {
            $where[] = "i.preco $operator :$input";
            $params[$input] = (float) $_GET[$input];
        }
    }
    foreach (['quartos', 'banheiros', 'vagas'] as $field) {
        if (($_GET[$field] ?? '') !== '') {
            $where[] = "i.$field >= :$field";
            $params[$field] = (int) $_GET[$field];
        }
    }
    $order = match ($_GET['ordem'] ?? '') {
        'menor-preco' => 'i.preco ASC',
        'maior-preco' => 'i.preco DESC',
        default => 'i.criado_em DESC',
    };
    $sql = 'SELECT i.*, c.nome AS categoria, ci.nome AS cidade FROM imoveis i '
        . 'JOIN categorias c ON c.id = i.categoria_id JOIN cidades ci ON ci.id = i.cidade_id '
        . 'WHERE ' . implode(' AND ', $where) . " ORDER BY $order LIMIT 30";
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

if ($url === '' || $url === 'buscar') {
    $statement = $db->query("SELECT i.*, c.nome AS categoria, ci.nome AS cidade FROM imoveis i JOIN categorias c ON c.id = i.categoria_id JOIN cidades ci ON ci.id = i.cidade_id WHERE i.status = 'publicado' ORDER BY i.criado_em DESC LIMIT 12");
    $properties = $url === '' && $method === 'GET' && count($_GET) <= 1 ? $statement->fetchAll() : propertyFilters($db);
    $categories = $db->query('SELECT id, nome FROM categorias WHERE ativo = 1 ORDER BY ordem, nome')->fetchAll();
    $cities = $db->query('SELECT id, nome, estado FROM cidades WHERE ativo = 1 ORDER BY nome')->fetchAll();
    render($url === '' ? 'home/index' : 'imovel/list', compact('properties', 'categories', 'cities'));
    exit;
}

if (preg_match('#^imovel/([a-z0-9-]+)$#', $url, $matches)) {
    $statement = $db->prepare("SELECT i.*, c.nome AS categoria, ci.nome AS cidade, u.nome AS anunciante FROM imoveis i JOIN categorias c ON c.id = i.categoria_id JOIN cidades ci ON ci.id = i.cidade_id JOIN usuarios u ON u.id = i.usuario_id WHERE i.slug = :slug AND i.status = 'publicado' LIMIT 1");
    $statement->execute(['slug' => $matches[1]]);
    $property = $statement->fetch();
    if (!$property) { http_response_code(404); render('errors/404'); exit; }
    $images = $db->prepare('SELECT * FROM imovel_imagens WHERE imovel_id = :id ORDER BY principal DESC, ordem');
    $images->execute(['id' => $property['id']]);
    render('imovel/show', ['property' => $property, 'images' => $images->fetchAll()]);
    exit;
}

if ($url === 'sair') {
    $_SESSION = [];
    session_destroy();
    Helper::redirect(Helper::url());
}

if ($url === 'entrar' || $url === 'cadastrar') {
    if ($method === 'POST') {
        $name = trim((string) ($_POST['nome'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['senha'] ?? '');
        if ($url === 'cadastrar') {
            $phone = trim((string) ($_POST['telefone'] ?? ''));
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
                Helper::setFlash('error', 'Informe nome, e-mail válido e senha com pelo menos 6 caracteres.');
            } else {
                try {
                    $statement = $db->prepare('INSERT INTO usuarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)');
                    $statement->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
                    Helper::setFlash('success', 'Conta criada. Faça seu login.');
                    Helper::redirect(Helper::url('entrar'));
                } catch (PDOException $exception) {
                    Helper::setFlash('error', 'Este e-mail já está cadastrado.');
                }
            }
        } else {
            $statement = $db->prepare("SELECT * FROM usuarios WHERE email = ? AND status = 'ativo' LIMIT 1");
            $statement->execute([$email]);
            $user = $statement->fetch();
            if ($user && password_verify($password, $user['senha'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['papel'];
                Helper::redirect(Helper::url('minha-conta'));
            }
            Helper::setFlash('error', 'E-mail ou senha inválidos.');
        }
    }
    render('auth/' . ($url === 'entrar' ? 'login' : 'register'));
    exit;
}

if ($url === 'anunciar' || $url === 'minha-conta') {
    requireLogin();
    if ($url === 'anunciar' && $method === 'POST') {
        $fields = ['finalidade', 'categoria_id', 'cidade_id', 'bairro', 'titulo', 'descricao', 'preco', 'quartos', 'banheiros', 'suites', 'vagas', 'area_construida', 'area_terreno', 'endereco', 'caracteristicas', 'nome_contato', 'telefone_contato'];
        $values = [];
        foreach ($fields as $field) { $values[$field] = trim((string) ($_POST[$field] ?? '')); }
        if ($values['titulo'] === '' || $values['descricao'] === '' || (float) $values['preco'] <= 0) {
            Helper::setFlash('error', 'Preencha título, descrição e preço.');
        } else {
            $slug = Helper::slugify($values['titulo'] . '-' . $values['cidade_id'] . '-' . time());
            $sql = 'INSERT INTO imoveis (usuario_id, categoria_id, cidade_id, bairro, titulo, slug, descricao, finalidade, preco, quartos, banheiros, suites, vagas, area_construida, area_terreno, endereco, caracteristicas, nome_contato, telefone_contato) VALUES (:usuario_id, :categoria_id, :cidade_id, :bairro, :titulo, :slug, :descricao, :finalidade, :preco, :quartos, :banheiros, :suites, :vagas, :area_construida, :area_terreno, :endereco, :caracteristicas, :nome_contato, :telefone_contato)';
            $values['usuario_id'] = Auth::id(); $values['slug'] = $slug;
            $statement = $db->prepare($sql); $statement->execute($values);
            Upload::saveMany($_FILES['fotos'] ?? [], (int) $db->lastInsertId());
            Helper::setFlash('success', 'Anúncio enviado para aprovação.');
            Helper::redirect(Helper::url('minha-conta'));
        }
    }
    if ($url === 'anunciar') {
        $categories = $db->query('SELECT id, nome FROM categorias WHERE ativo = 1 ORDER BY ordem, nome')->fetchAll();
        $cities = $db->query('SELECT id, nome, estado FROM cidades WHERE ativo = 1 ORDER BY nome')->fetchAll();
        render('usuario/create', compact('categories', 'cities')); exit;
    }
    $statement = $db->prepare('SELECT i.*, c.nome AS categoria, ci.nome AS cidade FROM imoveis i JOIN categorias c ON c.id = i.categoria_id JOIN cidades ci ON ci.id = i.cidade_id WHERE i.usuario_id = ? ORDER BY i.criado_em DESC');
    $statement->execute([Auth::id()]);
    render('usuario/dashboard', ['properties' => $statement->fetchAll()]); exit;
}

if (str_starts_with($url, 'admin')) {
    requireLogin();
    if (($_SESSION['user_role'] ?? '') !== 'admin') { http_response_code(403); echo 'Acesso restrito.'; exit; }
    if ($url === 'admin/aprovar' && isset($_GET['id'])) {
        $statement = $db->prepare("UPDATE imoveis SET status = 'publicado', publicado_em = NOW() WHERE id = ?");
        $statement->execute([(int) $_GET['id']]); Helper::redirect(Helper::url('admin'));
    }
    $properties = $db->query("SELECT i.*, c.nome AS categoria, ci.nome AS cidade FROM imoveis i JOIN categorias c ON c.id = i.categoria_id JOIN cidades ci ON ci.id = i.cidade_id WHERE i.status = 'pendente' ORDER BY i.criado_em")->fetchAll();
    render('admin/dashboard', compact('properties')); exit;
}

http_response_code(404);
render('errors/404');