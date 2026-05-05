<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/DataBase.php';

class EmailVerificationDao {
  private $conn;

  public function __construct()
  {
    // Conectar à base de dados
    $this->conn = (new DataBase())->connect();
  }

  public function createForUser($userId, $expirationTime = 300) {
    // Token que vai no link para o email
    $token = bin2hex(random_bytes(32)); // Gera um token aleatório de 32 caracteres
    $tokenHash = hash('sha256', $token); // Hash do token para armazenar na base de dados

    $sql = "
      INSERT INTO email_verifications (user_id, token_hash, expires_at, used_at, created_at)
      VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NULL, NOW())
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId, $tokenHash, $expirationTime]);

    return $token;

  }

  public function validateToken($token) {
    $tokenHash = hash('sha256', $token);

    $sql = "
      SELECT user_id
      FROM email_verifications
      WHERE token_hash = ?
        AND used_at IS NULL
        AND expires_at > NOW()
      ORDER BY id DESC
      LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);
    
    $stmt->execute([$tokenHash]);

    //$userId = $stmt->fetchColumn();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $userId = $row['user_id'] ?? null;

    return $userId ? (int)$userId : null;
  }

  public function markUsed(string $token): void
  {
    $tokenHash = hash('sha256', $token);

    $stmt = $this->conn->prepare("
      UPDATE email_verifications 
        SET used_at = NOW() 
      WHERE token_hash = ?"
    );
    
    $stmt->execute([$tokenHash]);
  }
}