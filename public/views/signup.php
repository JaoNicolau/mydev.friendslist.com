<?php include __DIR__ . "/../includes/header.php"; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<body class="bg-black">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card bg-black text-white" style="border: 2px solid white;">
                    <div class="card-body p-5">

                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="Images/imoral_logo1.png" alt="logo_imoral" class="mb-3" style="width: 200px;">
                            <h2 class="card-title">Sign Up</h2>
                        </div>

                        <form method="POST" action="/signup" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control bg-black text-white border-light" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control bg-black text-white border-light" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control bg-black text-white border-light" required>
                            </div>

                            <button type="submit" class="btn btn-outline-light w-100 mt-3">Sign Up</button>
                        </form>

                        <div class="text-center mt-3">
                            Já tem uma conta?
                            <a class="text-decoration-none" href="/login"> Faça login aqui.</a>
                        </div>

                        <div id="mensagem" class="mt-3 text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/signup.js"></script>
</body>
<?php include __DIR__ . "/../includes/footer.php"; ?>