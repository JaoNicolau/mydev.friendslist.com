<?php 
require_once __DIR__.'/../../vendor/autoload.php';

require_once __DIR__.'/../../app/utils/Utils.php';
require_once __DIR__.'/../../app/controllers/AuthController.php';
require_once __DIR__.'/../../app/controllers/UserController.php';
require_once __DIR__.'/../../app/mddleware/AuthMiddlewareApi.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

header('Content-Type: application/json; charset=UTF-8');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/api', '', $uri);

$method = $_SERVER['REQUEST_METHOD'];

if (($uri === "/" || $uri === "/index") && $method === 'GET') {
  Utils::jsonResponse([
    "success" => false,
    "message" => "id e nome são obrigatórios"
  ], 200); 
  exit;
}

else if ($uri === "/login" && $method === 'POST') {
    (new AuthController())->loginApi();
}

else if ($uri === "/home" && $method === 'GET') {
  $dataToken = AuthMiddlewareApi::check();

  $users = (new UserController())->getAllUsers($dataToken->id);
}

// Rota não encontrada
else {

  $dataResponse = [
    'success' => false,
    'message' => 'Rota não encontrada',
    'data' => []
  ];
  Utils::jsonResponse($dataResponse, 404);
}
?>
