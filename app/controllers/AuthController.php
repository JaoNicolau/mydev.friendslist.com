<?php
require_once __DIR__ . '/../dao/UserDAO.php';
require_once __DIR__ . '/../dao/EmailVerificationDAO.php';
require_once __DIR__ . '/../config/jwtConfig.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController{
 
  private function view($name, $data = []){
    extract($data, EXTR_SKIP);
   
    require __DIR__ . '/../../public/views/' . $name . '.php';
  }
 
  public function loginWeb() {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($email) || empty($password)) {
      die("Email e password são obrigatórios");
    }
 
    $user = (new UserDAO())->findByEmail($email);
    
    if(!$user) {
      die("Email ou password inválidos");
    }

    var_dump($user->getPassword());

    if(password_verify($password, $user->getPassword())) {
      var_dump("Password correta");

      $_SESSION['token'] = [
        'id' => $user->getId(),
        'username' => $user->getUsername(),
        'email' => $user->getEmail(),
        'is_admin' => $user->isAdmin()
      ];

      $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'Bem vindo de volta, ' . $user->getUsername() . "!"
      ];

      header("Location: /");

      exit;
    } else {
      $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Dados de login inválidos.'
      ];
      header("Location: /login");
      exit;
    }

  }
 
  public function signupWeb() {
    /**
     * @TODO Validar se existe user logado
     */
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
 
    if($username === '' || $email === '') {
      throw new Exception("Username e email são obrigatórios");
    }
 
    if(! filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Dados inválidos");
    }
 
    // Verificar se email já existe
    $user = (new UserDAO())->findByEmail($email);
 
    var_dump($user);
    if($user) {
      throw new Exception("Email já existe");
    }
    // User no estado pendente
    $userId = (new UserDAO())->createPending($username, $email);
   
    // Criar token de verificação
    $verDAO = new EmailVerificationDAO();
 
    $token = $verDAO->createForUser($userId, 300);
 
    // 3) baseUrl dinâmico (vhosts)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $baseUrl = $scheme . '://' . $host;
   
    // 4) link para clicar no email
    $link = $baseUrl . "/verify-email?token=" . urlencode($token);
   
    // 5) envia email via Mailer (PHPMailer/Mailtrap)
    $subject = "Verifica o teu email (expira em 5 min)";
    $html = "
        <div style='font-family: Arial, sans-serif;'>
        <h2>Olá, " . htmlspecialchars($username . $userId) . "!</h2>
        <p>Para ativares a tua conta e definires a tua password, clica no link abaixo (válido por <b>5 minutos</b>):</p>
        <p><a href='{$link}'>{$link}</a></p>
        <p>Se o link expirar, faz signup novamente (ou pede reenvio do link).</p>
        </div>
        ";
 
    (new Mailer())->send($email, $subject, $html);
 
    // 6) redirect com toast
    $_SESSION['toast'] = [
      'type' => 'success',
      'message' => "Conta criada. Enviámos um email para verificares (link expira em 5 min)."
    ];
    header("Location: /login");
    exit;
 
  }
 
  public function verifyEmailForm() {
    $token = $_GET['token'] ?? '';
   
    if(empty($token)) {
      header("Location: /bad-request");
    }
 
    $this->view('verify-email', ['token' => $token]);
 
  }
 
  public function verifyEmailSubmit() {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
 
    if(empty($token) || empty($password)) {
      throw new Exception("Dados inválidos");
    }
 
    // Verificar validade do token
    $verDAO = new EmailVerificationDAO();
 
    $userId = $verDAO->validateToken($token);
 
    //var_dump("UserId encontrado para o token: " . $userId);
    if(!$userId) {
      throw new Exception("Token inválido ou expirado");
    }
 
    $hash = password_hash($password, PASSWORD_DEFAULT);
 
    $userDao = new UserDAO();
    $userDao->setPasswordAndVerify($userId, $hash);
 
    $verDAO->markUsed($token);
 
    $_SESSION['toast'] = [
      'type' => 'success',
      'message' => "Email verificado e password definida. Já podes fazer login."
    ];
    header("Location: /login");
    exit;
  }

  public function logoutWeb() {
    unset($_SESSION['token']);

    $_SESSION['toast'] = [
      'type' => 'success',
      'message' => 'Logout efetuado com sucesso.'
    ];

    
    header("Location: /");
    exit;
  }

  public function loginApi() {
    try {
      $body = json_decode(file_get_contents('php://input'), true);

      $email    = trim($body['email'] ?? $_POST['email'] ?? '');
      $password = trim($body['password'] ?? $_POST['password'] ?? '');

      // Se não houver email ou password, mostrar erro
      // é preciso lançar exceção para o index.php apanhar e mostrar o erro via flash message
      if (empty($email) || empty($password)) {
        throw new Exception("Email e password são obrigatórios");
      }
 
      $user = (new UserDAO())->findByEmail($email);
 
      if (! $user || ! password_verify($password, $user->getPassword())) {
        throw new Exception("Email ou password errados");
      }
 
      $data = [
        'id' => $user->getId(),
        'role' => $user->isAdmin()
      ];

      $payload = jwtConfig::getConfig($data);
 
      $jwt = JWT::encode($payload, jwtConfig::getSignature(), "HS256");
 
      $dataResponse = [
        'success' => true,
        'message' => "Login efetuado com sucesso",
        'data'    => [
          'jwt' => $jwt,
          'user' => [
            'id' => $user->getId(),
            'is_admin' => $user->isAdmin(),
            'username' => $user->getUsername(),
          ]
        ]
      ];
 
      Utils::jsonResponse($dataResponse, 200);
 
    } catch(Exception $e) {
      $dataResponse = [
        'success' => false,
        'message' => $e->getMessage(),
        'data'    => []
      ];
 
      Utils::jsonResponse($dataResponse, 401);
    }
  }
}