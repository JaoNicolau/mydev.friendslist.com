<?php
session_start();

// IMPORTS
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . "/../app/controllers/WebController.php";
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/UserController.php";
require_once __DIR__ . "/../app/services/Mailer.php";
require_once __DIR__ . "/../app/mddleware/AuthMiddlewareWeb.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace("mydev.imoral.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

//var_dump($_SESSION);

if($uri === '/' || $uri === '/index' || $uri === '/home') {
    (new WebController())->index();
}

elseif($uri === '/pagina-privada' && $method === 'GET') {
    $isLogin = AuthMiddlewareWeb::isLogin();

    if(!$isLogin) {
        header("Location: /login");
        exit;
    } else {
        die("Aceder à página privada");
    }
}

elseif($uri === '/login' && $method === 'GET') {
    if(AuthMiddlewareWeb::isLogin()) {
        header("Location: /");
        exit;
    } else {
        (new WebController())->login();
    }
}

elseif($uri === '/login' && $method === 'POST') {
    (new AuthController())->loginWeb();
}

elseif($uri === '/logout' && $method === 'GET') {
    if(AuthMiddlewareWeb::isLogin()) {
        (new AuthController())->logoutWeb();
        exit;
    } else {
        header("Location: /");
        exit;
    }
}

elseif($uri === '/signup' && $method === 'GET') {
    (new WebController())->signup();
}

elseif($uri === '/signup' && $method === 'POST') {

    try {
        (new AuthController())->signupWeb();

    } catch (Exception $e) {

        var_dump($e);
        var_dump($e->getMessage());
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
        header("Location: /signup");
        exit();
    }
    

}

elseif($uri === '/verify-email' && $method === 'GET') {
    // var_dump("Entrar na página de verificação de email");    
    (new AuthController())->verifyEmailForm();
}

elseif($uri === '/verify-email' && $method === 'POST') {
    try {
        (new AuthController())->verifyEmailSubmit();
    } catch (Exception $e) {
        var_dump($e);
        var_dump($e->getMessage());
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
        header("Location: /verify-email");
        exit();
    }
}

elseif($uri === '/users' && $method === 'GET') {
    try{
        (new UserController())->getUsers();
    } catch (Exception $e) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
        header("Location: /users");
        exit();
    }
}

elseif(preg_match('#^/users\/(\d+)$#', $uri, $m) && $method === 'GET') {
    (new UserController())->profile($m[1]);
}

elseif(preg_match('#^/users\/(\d+)/update$#', $uri, $m) && $method === 'POST') {
    try{
        (new UserController())->update($m[1]);

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Perfil atualizado com sucesso!'
        ];
        header("Location: /users/{$m[1]}");
        exit();
    } catch (Exception $e) {
        var_dump($e);
        var_dump($e->getMessage());
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
        header("Location: /users/{$m[1]}");
        exit();
    }
}

elseif($uri === '/dashboard' && $method === 'GET') {
    if(!AuthMiddlewareWeb::isAdmin()) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Acesso negado. Apenas administradores podem acessar o dashboard.'
        ];
        header("Location: /home");
        exit;
    } else {
        (new WebController())->dashboard();
    }
}

// Rota das páginas de erro
elseif($uri === '/bad-request' && $method === 'GET') {
    (new WebController())->badRequest();
}

else {
    echo "404 - Página não encontrada.";
}