<?php $flash = Helper::getFlash(); ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Helper::e($title ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= Helper::e(SITE_DESCRIPTION) ?>">
    <meta property="og:title" content="<?= Helper::e($title ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= Helper::e(SITE_DESCRIPTION) ?>">
    <link rel="stylesheet" href="<?= Helper::e(Helper::url('assets/css/app.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= Helper::e(Helper::url()) ?>">Lar<span>Perto</span></a>
        <nav>
            <a href="<?= Helper::e(Helper::url('buscar')) ?>">Comprar / Alugar</a>
            <a href="<?= Helper::e(Helper::url('anunciar')) ?>">Anunciar imóvel</a>
            <?php if (Auth::check()): ?>
                <a class="nav-button" href="<?= Helper::e(Helper::url('minha-conta')) ?>">Minha conta</a>
                <a href="<?= Helper::e(Helper::url('sair')) ?>">Sair</a>
            <?php else: ?>
                <a class="nav-button" href="<?= Helper::e(Helper::url('entrar')) ?>">Entrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<?php if ($flash): ?><div class="container flash <?= Helper::e($flash['type']) ?>"><?= Helper::e($flash['message']) ?></div><?php endif; ?>
<main>