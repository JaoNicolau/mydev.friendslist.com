<?php
session_start();
// IMPORTS
require __DIR__ . '/../vendor/autoload.php';


require "../app/controllers/WebController.php";
require "../app/controllers/AuthController.php";
require "../app/services/Mailer.php";
require "../app/middleware/AuthMiddlewareWeb.php";



var_dump($_SESSION['token']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/' || $uri === '/index' || $uri === '/home') {
  (new WebController())->index();
}

elseif($uri === '/pagina-privada' && $method === 'GET') {
  $isLogin = AuthMiddlewareWeb::isLogin();

  if(! $isLogin) {
    $_SESSION['toast'] = [
      'type' => 'error',
      'message' => 'Página protegida!!!'
    ];


    header("Location: /login");
    exit;
  }

  var_dump("Podes continuar");
} elseif ($uri === '/pagina-privada-admin' && $method === 'GET') {
  $isAdmin = AuthMiddlewareWeb::isAdmin();
  var_dump($_SESSION['token']);
  var_dump($isAdmin);
  if (! $isAdmin) {
    header("Location: /");
    exit;
  }

  var_dump("Podes continuar porque é admin");
}








elseif($uri === '/login' && $method === 'GET') {
  $isLogin = AuthMiddlewareWeb::isLogin();
  // Se estiver logado não deico entrar no login
  if ($isLogin) {
    header("Location: /");
    exit;
  }


  (new WebController())->login();
}

elseif ($uri === '/login' && $method === 'POST') {
  //var_dump($email);
  //var_dump($password);

  //var_dump($_POST);

  //var_dump("Estou no login a validar os dados");
  (new AuthController())->loginWeb();
}

elseif($uri === '/logout' && $method === 'GET') {
  unset($_SESSION['token']);

  $_SESSION['toast'] = [
    'type' => 'success',
    'message' => 'Logout efetuado com sucesso!!!'
  ];


  header("Location: /login");
  exit;  

}



elseif($uri === '/signup' && $method === 'GET') {
  (new WebController())->signup();
}

elseif ($uri === '/signup' && $method === 'POST') {
  try {
    (new AuthController())->signupWeb();
  } catch (Exception $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    // Redirecionar de volta para a página de signup
    header("Location: /signup");
    exit();
  }
}

elseif($uri === '/verify-email' && $method === 'GET') {
  

  (new AuthController())->verifyEmailForm();

}

elseif ($uri === '/verify-email' && $method === 'POST') {
  try{
    (new AuthController())->verifyEmailSubmit();
  } catch (Exception $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    // Redirecionar de volta para a página de signup
    header("Location: /verify-email?token=" . urlencode($_POST['token']));
    exit();
  }
}

elseif($uri === '/send-email/test' && $method === 'GET') {
  var_dump('/send-email/test');

  $html = file_get_contents(__DIR__ . "/views/emails/welcome.php");

  (new Mailer())->send(
    "jose.goncalves@esjaloures.org",
    "Test Email MyDevPiratas",
    $html
  );

}

//Errors Pages
elseif($uri === '/bad-request') {
  (new WebController())->badRequest();
}


else {
  http_response_code(404);
  echo "Page not found";
}



