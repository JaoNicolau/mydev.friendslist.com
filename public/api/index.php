<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../../app/Utils/utils.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// 1 - Fazer o set do header
header("Content-Type: application/json; charset=UTF-8");

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
// var_dump($uri);

$uri = str_replace("/api", "", $uri);
// var_dump($uri);
$method = $_SERVER["REQUEST_METHOD"];

if (($uri === "/" || $uri === "/index") && $method === 'GET') {
    Utils::jsonResponse([
        "success" => false,
        "message" => "id e nome são obrigatórios"
    ], 200);
    exit;
} elseif ($uri === "/login" && $method === "POST") {
    (new AuthController())->loginApi();
} else {
    $responseData = [
        'sucess' => false,
        'message' => 'Rota não encontrada',
        'data' => []
    ];

    Utils::jsonResponse($responseData, 404);
}
// 2 - A forma da resposta
// $responseData = [
//     'sucess' => true,
//     'message' => 'Bem-vindo ao sistema, meu caro utilizador maravilhoso',
//     'data' => []
// ];

// Utils::jsonResponse($responseData, 200);

?>