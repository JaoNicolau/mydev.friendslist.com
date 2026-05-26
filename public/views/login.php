<?php include __DIR__ . "/../includes/header.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imoral - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-black">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card bg-black text-white" style="border: 2px solid white;">
                    <div class="card-body p-5">

                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="Images/imoral_logo1.png" alt="logo_imoral" class="mb-3" style="width: 200px;">
                            <h2 class="card-title">Login</h2>
                        </div>

                        <form method="POST" action="/login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control bg-black text-white border-light" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control bg-black text-white border-light" required>
                            </div>

                            <button type="submit" class="btn btn-outline-light w-100 mt-3">Entrar</button>
                        </form>

                        <div class="text-center mt-3">
                            Não tem uma conta?
                            <a class="text-decoration-none" href="/signup"> Registre-se aqui.</a>
                        </div>

                        <div id="mensagem" class="mt-3 text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/login.js"></script>
</body>
<?php include __DIR__ . "/../includes/footer.php"; ?>