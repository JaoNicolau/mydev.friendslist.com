<?php include __DIR__ . "/../includes/header.php"; ?>



<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Login</h4>
                    <form method="POST" action="/login">
                        <input name="email" class="form-control mb-2" placeholder="Email">
                        <input name="password" type="password" class="form-control mb-3" placeholder="Password">

                        <button class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <div class="text-center mt-3">
                        <a class="text-decoration-none" href="/signup">Não tem uma conta? Registre-se aqui.</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>