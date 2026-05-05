<?php include __DIR__ . "/../includes/header.php"; ?>

<?PHP if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['error']; ?>
  </div>

  <?php unset($_SESSION['error']); ?>
<?PHP endif; ?>




<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Sign up</h4>

          <form method="POST" action="/signup">
            <input name="username" value="Albertina" class="form-control mb-2" placeholder="Username" required>
            <input name="email" value="albertina.meirelles@gmail.com" type=" email" class="form-control mb-2" placeholder="Email" required>
            <input name="password" value="123456" type="password" class="form-control mb-3" placeholder="Password" required>

            <button class="btn btn-primary w-100">Criar conta</button>
          </form>

          <div class="text-center mt-3">
            <a href="/login" class="text-decoration-none">Já tenho conta</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include __DIR__ . "/../includes/footer.php"; ?>