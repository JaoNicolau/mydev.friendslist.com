<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="container py-5">

  <!-- HERO -->
  <div class="p-5 mb-4 bg-light border rounded-3">
    <div class="container-fluid py-4">
      <h1 class="display-5 fw-bold">MyDevPiratas</h1>
      <p class="col-md-8 fs-5">
        Área de login + dashboard + painel de admin e listagem de piratas.
      </p>
      <div class="d-flex gap-2">
        <a href="/login" class="btn btn-primary btn-lg">Entrar</a>
        <a href="/piratas" class="btn btn-outline-secondary btn-lg">Ver Piratas</a>
      </div>
    </div>
  </div>

  <!-- 3 CARDS -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Dashboard</h5>
          <p class="card-text">Resumo rápido e acesso às funcionalidades principais.</p>
          <a href="/dashboard" class="btn btn-dark">Abrir</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Piratas</h5>
          <p class="card-text">Lista de piratas carregada pela API.</p>
          <a href="/piratas" class="btn btn-dark">Ver lista</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Admin</h5>
          <p class="card-text">Gestão (apenas para utilizadores com role <code>admin</code>).</p>
          <a href="/admin" class="btn btn-dark">Painel admin</a>
        </div>
      </div>
    </div>
  </div>

  <!-- INFO STRIP -->
  <div class="row mt-5 g-4">
    <div class="col-md-6">
      <div class="border rounded p-4 h-100">
        <h4 class="mb-2">API</h4>
        <p class="mb-0">
          Endpoints: <code>/api/login</code>, <code>/api/piratas</code>.
        </p>
      </div>
    </div>

    <div class="col-md-6">
      <div class="border rounded p-4 h-100">
        <h4 class="mb-2">Segurança</h4>
        <p class="mb-0">
          JWT para API e sessão para a web (middleware).
        </p>
      </div>
    </div>
  </div>

</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>