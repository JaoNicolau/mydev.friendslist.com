<?php include __DIR__ . "/../../includes/header.php"; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger">
    <?php echo $_SESSION['flash_error']; ?>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
 
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Editar Profile</h4>
 
          <form method="POST" action="/users/<?= $user->getId(); ?>/update">
            <input name="username" value="<?= $user->getUsername(); ?>" class="form-control mb-2" placeholder="Username" required>
            <input name="email" value="<?= $user->getEmail(); ?>" type="email" class="form-control mb-2" placeholder="Email" required>
            <?php if(AuthMiddlewareWeb::canEditProfile($user->getId())): ?>
              <button class="btn btn-primary w-100">Guardar</button>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . "/../../includes/footer.php"; ?>