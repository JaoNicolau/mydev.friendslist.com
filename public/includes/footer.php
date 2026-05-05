<footer class="bg-dark text-white mt-5">
  <div class="container py-4">
    <div class="row align-items-center">
      <div class="col-md-6 mb-2 mb-md-0">
        <strong>MyDevPiratas</strong>
        <div class="text-white-50 small">
          <?= date("Y") ?> &copy; Todos os direitos reservados a jarg.
        </div>
      </div>

      <div class="col-md-6 text-md-end">
        <a class="text-white text-decoration-none me-3" href="/dashboard">Dashboard</a>
        <a class="text-white text-decoration-none me-3" href="/piratas">Piratas</a>
        <?php if (! AuthMiddlewareWeb::isLogin()): ?>

          <a class="text-white text-decoration-none" href="/login">Login</a>
        <?php else: ?>

          <a class="text-white text-decoration-none" href="/logout">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</footer>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  const toast = <?= json_encode($_SESSION["toast"] ?? null) ?>;

  <?php
  unset($_SESSION['toast']);
  ?>

  if (toast) {

    toastr[toast.type](toast.message);

  }
</script>

</body>

</html>