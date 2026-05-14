<?php
require __DIR__ . '/../vendor/autoload.php';

session_start();

//Imports
require "../app/controllers/WebController.php";
require "../app/controllers/AuthController.php";
require "../app/controllers/UserController.php";
require "../app/services/Mailler.php";
require "../app/middleware/AuthMiddlewareWeb.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri == '/' || $uri ==='/index'|| $uri === '/home') {
    //var_dump("Estou na home");
    (new WebController())->index();
}

elseif($uri === '/pagina_privada' && $method === 'GET') {

    $_SESSION['toast'] = [
        'type' => 'error',  
        'message'=> 'Pagina protegida!!!'
    ];

        header('location: /index');

    $isLogin = AuthMiddlewareWeb::islogin();

    if(!$isLogin) {
        header("Location: /login");
        exit();
    }
    var_dump("Podes continuar");
}

elseif($uri === '/pagina_privada_admin' && $method === 'GET') {

    $isLogin = AuthMiddlewareWeb::isAdmin();

    if(!$isLogin) {
        header("Location: /login");
        exit();
    }
    var_dump("Podes continuar porque és admin");
}


elseif($uri === '/about' && $method === 'GET') {
    (new WebController())->about();
}

elseif($uri === '/login' && $method === 'GET') {
    //var_dump("Estou na página de login");

    $isLogin = AuthMiddlewareWeb::isLogin();

    if($isLogin) {
        header("Location: /index");
        exit();
    }
    (new WebController())->login();
}

elseif($uri === '/logout' && $method === 'GET') {
    unset($_SESSION['token']);


    $_SESSION['toast'] = [
        'type' => 'success',
        'message'=> 'Logout efetuado com sucesso'
    ];
    
    header('Location: /login');
}

elseif($uri === '/login' && $method === 'POST') {

    //Apanhar os dados do formulario
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    //var_dump($email);
    //var_dump($password);

    //var_dump($_POST);
    
    (new AuthController())->loginWeb();

}

elseif($uri === '/signup' && $method === 'GET') {
    (new WebController())->signup();

}

elseif($uri === '/signup' && $method === 'POST') {
    try {
        (new AuthController())->signupWeb();
    } catch (Exception $e) {
        var_dump($e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header("Location: /signup");
        exit();
    }
}

elseif($uri === '/verify-email' && $method === 'GET') {

    (new AuthController())->verifyEmailForm();
}

elseif($uri === '/verify-email' && $method === 'POST') {

    try {
        (new AuthController())->verifyEmailSubmit();

    }catch (Exception $e) {
        var_dump($e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header("Location: /verify-email?token=" . urlencode($_POST['token'] ?? ''));
        exit();

    }
}

elseif($uri === '/users' && $method === 'GET') {

    var_dump('users');

    (new UserController())->listAll();
}

elseif(preg_match('#^/users/(\d+)$#', $uri, $m) && $method === 'GET'){

    echo '<br/>';
    var_dump($uri);
    var_dump($m[1]);
    var_dump('PRofile do user');

    (new UserController())->user($m[1]);
}

elseif(preg_match('#^/users/(\d+)$#', $uri, $m) && $method === 'POST'){

    echo '<br/>';
    var_dump($uri);
    var_dump($m[1]);
    var_dump('PRofile do user');
    try {

        $_SESSION['toast'] =['type'=> 'success','message' => 'Atualização realizada com sucesso!!!'];

        (new UserController())->userUpdate($m[1]);

        header("Location: /users/$m[1]");
        
    }catch(Exception $e) {

        $_SESSION['toast'] =['type'=> 'error', 'message'=> $e->getMessage()];

        header("Location: /users/$m[1]");
    }


} elseif (preg_match('#^/users/(\d+)/delete$#', $uri, $m) && $method === 'GET') {
    echo '<br/>';
    var_dump($uri);
    var_dump($m[1]);
    var_dump('Delete do user');

    try {
    (new UserController())->userDelete($m[1]);

    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'Atualização realizada com muito sucesso!!!'
    ];

    header("Location: /users");
    } catch (Exception $e) {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => $e->getMessage()
    ];

    header("Location: /users/$m[1]");
    }
}



elseif ($uri === '/send-email/test' && $method === 'GET') {
    var_dump('/send-email/test');

    $html = file_get_contents(__DIR__ . "/views/emails/welcome.php");

    var_dump($html);


    (new Mailer())->send(
        "37613@esjaloures.org",
        "Teste Email",
        $html
    );
}

elseif($uri === '/bad-request') {

    (new WebController())->badRequest();
}

else {
    http_response_code(404);
    echo "Página não encontrada";
}
