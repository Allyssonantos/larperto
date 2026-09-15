<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e(SITE_NAME) ?> - Imóveis em Posse-GO</title>
    <meta name="description" content="<?= Helper::e(SITE_DESCRIPTION) ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="<?= Helper::url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= Helper::url() ?>">
            <i class="bi bi-house-heart"></i> LarPerto
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= Helper::url('buscar?finalidade=venda') ?>">Comprar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Helper::url('buscar?finalidade=aluguel') ?>">Alugar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Helper::url('anunciar') ?>">Anunciar Imóvel</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= Helper::url('entrar') ?>">Entrar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light text-primary px-3 ms-2" href="<?= Helper::url('cadastrar') ?>">Cadastrar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main></main>