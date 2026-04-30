<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../config/Database.php";

class EmailVerificationDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function createForUser($userId, $expirationTime = 300)
    {
        $token = bin2hex(random_bytes(32)); // Generate a random token
        $tokenHash = hash('sha256', $token); // Hash the token for secure storage

        $sql = "
      INSERT INTO email_verifications (user_id, token_hash, expires_at, used_at, created_at)
      VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NULL, NOW())
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $tokenHash, $expirationTime]);

        return $token;
    }
}