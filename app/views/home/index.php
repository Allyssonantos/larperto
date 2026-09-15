<?php $title = 'Imóveis para comprar e alugar em Posse-GO'; ?>
<section class="hero container">
    <div class="hero-copy"><p class="eyebrow">Posse-GO e região</p><h1>O imóvel certo começa mais perto.</h1><p>Encontre casas, terrenos e espaços comerciais anunciados por pessoas da sua região.</p></div>
    <form class="search-panel" action="<?= Helper::e(Helper::url('buscar')) ?>" method="get">
        <label>O que você procura<select name="finalidade"><option value="">Comprar ou alugar</option><option value="venda">Comprar</option><option value="aluguel">Alugar</option></select></label>
        <label>Tipo<select name="categoria_id"><option value="">Todos os tipos</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>"><?= Helper::e($category['nome']) ?></option><?php endforeach; ?></select></label>
        <label>Cidade<select name="cidade_id"><option value="">Toda a região</option><?php foreach ($cities as $city): ?><option value="<?= (int) $city['id'] ?>"><?= Helper::e($city['nome']) ?>-<?= Helper::e($city['estado']) ?></option><?php endforeach; ?></select></label>
        <button class="button" type="submit">Buscar imóveis</button>
    </form>
</section>
<section class="container section"><div class="section-heading"><div><p class="eyebrow">Novidades</p><h2>Imóveis recentes</h2></div><a href="<?= Helper::e(Helper::url('buscar')) ?>">Ver todos</a></div><?php require APP_PATH . '/views/imovel/_cards.php'; ?></section>
<section class="container callout"><div><p class="eyebrow">Anúncio gratuito</p><h2>Seu imóvel pode ser o próximo destaque.</h2><p>Cadastre seu anúncio e alcance pessoas procurando na região.</p></div><a class="button button-light" href="<?= Helper::e(Helper::url('anunciar')) ?>">Anunciar imóvel</a></section>