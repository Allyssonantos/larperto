</main>

<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">LarPerto</h5>
                <p class="text-white-50">Imóveis para venda e aluguel em Posse-GO e região. Anuncie grátis!</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= Helper::url() ?>" class="text-white-50 text-decoration-none">Início</a></li>
                    <li><a href="<?= Helper::url('anunciar') ?>" class="text-white-50 text-decoration-none">Anunciar Imóvel</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Sobre</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Contato</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Contato</h5>
                <p class="text-white-50 mb-1">
                    <i class="bi bi-whatsapp"></i> (64) 99999-9999
                </p>
                <p class="text-white-50">
                    <i class="bi bi-envelope"></i> contato@larperto.com.br
                </p>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-white-50">
            <small>&copy; <?= date('Y') ?> LarPerto. Todos os direitos reservados.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= Helper::url('assets/js/app.js') ?>"></script>
</body>
</html>