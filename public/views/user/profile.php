<?php include __DIR__ . "/../../includes/header.php"; ?>

<?PHP if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
    </div>

    <?php unset($_SESSION['error']); ?>
<?PHP endif; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">

            <?php if ($user->isAdmin()): ?>

                <div class="alert alert-primary" role="alert">
                    É Administrador
                </div>

            <?php else: ?>
                <div class="alert alert-secondary" role="alert">
                    Não É Admin
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Profile</h4>

                    <form method="POST" action="/users/<?= $user->getId() ?>">
                        <input name="username" value="<?= $user->getUsername() ?>" class="form-control mb-2"
                            placeholder="Username" required <?php if (!AuthMiddlewareWeb::canEdit($user->getId())): ?>
                                disabled <?php endif ?>>
                        <input name="email" value="<?= $user->getEmail() ?>" type="email" class="form-control mb-2"
                            placeholder="Email" required>
                        <input id="is_admin" name="is_admin" type="checkbox" class="form-check-input"
                            <?= $user->isAdmin() ? 'checked' : '' ?>>
                        <label for="is_admin">Admin</label>

                        <?php if (AuthMiddlewareWeb::canEdit($user->getId())): ?>
                            <button class="btn btn-primary w-100">Guardar</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . "/../../includes/footer.php"; ?>