<?php include __DIR__ . "/../../includes/header.php"; ?>
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<main class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 denied-card">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="mx-auto mb-3 icon-wrap">
                            <!-- Ícone (Bootstrap Icons inline em SVG) -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                viewBox="0 0 16 16" aria-hidden="true">
                                <path
                                    d="M8 1a2 2 0 0 0-2 2v4H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM7 3a1 1 0 0 1 2 0v4H7V3z" />
                            </svg>
                        </div>

                        <h1 class="h3 mb-2">Acesso negado</h1>
                        <p class="text-secondary mb-4">
                            Você não tem permissões para aceder a esta página.
                            Se acha que isto é um erro, contacte o administrador.
                        </p>

                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a class="btn btn-primary" href="/">Voltar ao início</a>
                            <a class="btn btn-outline-secondary" href="javascript:history.back()">Página anterior</a>
                        </div>

                        <hr class="my-4" />

                        <small class="text-muted">
                            Código do erro: <strong>400</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . "/../../includes/footer.php"; ?>