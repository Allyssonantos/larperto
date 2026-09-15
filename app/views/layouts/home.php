<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Encontre seu próximo lar em Posse-GO</h1>
        <p class="lead text-muted">Casas, apartamentos, terrenos e muito mais. Anuncie grátis!</p>
    </div>

    <!-- Formulário de busca (será implementado depois) -->
    <div class="card shadow-sm mb-5">
        <div class="card-body p-4">
            <form action="<?= Helper::url('buscar') ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="finalidade" class="form-select">
                            <option value="">Finalidade</option>
                            <option value="venda">Comprar</option>
                            <option value="aluguel">Alugar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select">
                            <option value="">Tipo de imóvel</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="terreno">Terreno</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="cidade" class="form-select">
                            <option value="posse-go">Posse - GO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Buscar imóveis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center">
        <a href="<?= Helper::url('anunciar') ?>" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Anunciar meu imóvel grátis
        </a>
    </div>
</div>