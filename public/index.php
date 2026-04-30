<?php

session_start();

//IMPORTS

require __DIR__ . '/../vendor/autoload.php';

require "../app/controllers/WebController.php";
require "../app/controllers/AuthController.php";
require "../app/controllers/UserController.php";
require "../app/services/Mailer.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 
$uri = str_replace("mydevpiratas.com/public", "", $uri);
 
$method = $_SERVER['REQUEST_METHOD'];
 
if ($uri === '/' || $uri === '/index' || $uri === '/home') {
  (new WebController())->index();
 
} else if ($uri === '/login' && $method === 'GET') {
  (new WebController())->login();
} else if ($uri === '/login' && $method === 'POST') {
  // Apanhar os dados do formulário
 
 
  //var_dump($email, $password);
 
  (new AuthController())->loginWeb();
} else if ($uri === '/signup' && $method === 'GET') {
  (new WebController())->signup();
} else if ($uri === '/signup' && $method === 'POST') {
  try {
    (new AuthController())->signupWeb();
  } catch (Exception $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    // Redirecionar para a página de registo
    header('Location: /signup');
    exit();
  }


} else if ($uri === '/about') {
  (new WebController())->about();
} else if ($uri === '/send-email/test' && $method === 'GET') {
  var_dump('/send-email/test');

  $html = file_get_contents(__DIR__ . '/views/emails/welcome.php');


  (new Mailer())->send(
    "36922@esjaloures.org",
    "Test Email",
    $html 
  );
} 

else if ($uri === "/verify-email" && $method === "GET") {
  (new AuthController())->verifyEmailForm();
}


else if ($uri === "/get-all-users" && $method === "GET") {
  (new UserController())->getAllUsers();

}



// Erros Pages
else if ($uri === "/bad-request") {
  (new WebController())->badRequest();
}



else {
  http_response_code(404);
  echo "404 Not Found";
}