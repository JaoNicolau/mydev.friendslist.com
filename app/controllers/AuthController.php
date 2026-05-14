<?php

require __DIR__ . '/../dao/UserDAO.php';
require __DIR__ . '/../dao/emailVerificationDAO.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthController
{

    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function loginWeb()
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $passwordEncript = password_hash($password, PASSWORD_DEFAULT);
        //var_dump($passwordEncript);

        //Se n tiver email ou password, mostrar erro
        if (empty($email) || empty($password)) {
            die("Email e password são obrigatórios");
        }

        $user = (new UserDAO())->findByEmail($email);

        var_dump($user);

        if (!$user) {
            die("Email ou password inválidos");
        }
        // serve para criar a session token 
        // que valida se o user esta logado ou nao

        $_SESSION['token'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'is_admin' => $user->isAdmin(),
            'is_verified' => $user->is_Verified(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
            'deleted_at' => $user->getDeletedAt(),
        ];

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Login efetuado com sucesso'
        ];

        header('location: /index');
    }

    public function signupWeb()
    {

        /*
         * @TODO validar se existe utilizador logado
         */

        $username = trim($_POST["username"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $password = trim($_POST["password"] ?? '');

        if ($username === '' || $email === '') {
            die("Todos os campos são obrigatórios");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        $user = (new UserDAO())->findByEmail($email);

        if ($user) {
            throw new Exception("Email já existe");
        }

        //Criar um utilizador no estad o pendente
        $userDAO = new UserDAO();

        $userId = $userDAO->createPending($username, $email);

        $verDAO = new emailVerificationDAO();

        $token = $verDAO->createForUser($userId, 300);

        // 3) baseUrl dinâmico (vhosts)
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;

        // 4) link para clicar no email
        $link = $baseUrl . "/verify-email?token=" . urlencode($token);

        // 5) envia email via Mailer (PHPMailer/Mailtrap)
        $subject = "Verifica o teu email (expira em 5 min)";
        $html = "
            <div style='font-family: Arial, sans-serif;'>
            <h2>Olá, " . htmlspecialchars($username) . "!</h2>
            <p>Para ativares a tua conta e definires a tua password, clica no link abaixo (válido por <b>5 minutos</b>):</p>
            <p><a href='{$link}'>{$link}</a></p>
            <p>Se o link expirar, faz signup novamente (ou pede reenvio do link).</p>
            </div>
        ";

        (new Mailer())->send($email, $subject, $html);

        // 6) redirect com toast
        $_SESSION['flash_success'] = "Conta criada. Enviámos um email para verificares (link expira em 5 min).";
        header("Location: /login");
        exit;
    }

    public function verifyEmailForm()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            header("Location: /bad-request");
            exit;
        }

        //Token valido
        $this->view('verify-email', ['token' => $token]);

    }

    public function verifyEmailSubmit()
    {

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($token) || empty($password)) {
            throw new Exception("Token e password são obrigatórios");
        }

        $verDAO = new emailVerificationDAO();

        $userID = $verDAO->validadeToken($token);

        var_dump($userID);

        if (!$userID) {
            throw new Exception("Token inválido ou expirado");
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $userDAO = new UserDAO();

        //Atualizar a password do user e marcar como verificado
        $userDAO->setPasswordAndVerify($userID, $hash);

        $verDAO->markUsed($token);

        $_SESSION['flash_success'] = "Email verificado e password definida. Já podes fazer login.";
        header("Location: /login");
        exit;

    }

    public function loginApi()
    {
        try {

            var_dump("loginApi");
            var_dump($_POST);
            $email = trim($_POST['email']) ?? '';

            $password = trim($_POST['password']) ?? '';
            // Se não houver email ou password, mostrar erro
            // é preciso lançar exceção para o index.php apanhar e mostrar o erro via flash message
            if (empty($email) || empty($password)) {
                throw new Exception("Email e password são obrigatórios");
            }

            $user = (new UserDAO())->findByEmail($email);

            if (!$user || !password_verify($password, $user->getPassword())) {
                throw new Exception("Email ou password errados");
            }

            $payload = [
                'iat' => time(),
                'exp' => time() + 3600,
                "data" => [
                    'id' => $user->getId(),
                    'role' => $user->isAdmin()
                ]
            ];

            $jwt = JWT::encode($payload, "FCP", "HS256");

            $dataResponse = [
                'success' => true,
                'message' => "Login efetuado com sucesso",
                'data' => [
                    'jwt' => $jwt,
                    'user' => [
                        'id' => $user->getId(),
                        'role' => $user->isAdmin(),
                        'username' => $user->getUsername(),
                    ]
                ]
            ];

            Utils::jsonResponse($dataResponse, 200);




        } catch (Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];

            Utils::jsonResponse($dataResponse, 401);
        }
    }
}