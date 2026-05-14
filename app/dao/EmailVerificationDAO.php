<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../models/User.php';

class emailVerificationDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function createForUser($userId, $expirationTime = 300)
    {
        //token que vai na url
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $sql = "
        INSERT INTO email_verifications (user_id, token_hash, expires_at, used_at, created_at)
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NULL, NOW())
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $tokenHash, $expirationTime]);

        return $token;
    }

    public function validadeToken($token)
    {
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

        //mesma coisa do de baixo
        // $userId = $stmt->fetchColumn();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $row["user_id"] ?? null;

        return $userId ? (int) $userId : null;
    }

    public function markUsed(string $token): void
    {
        $tokenHash = hash('sha256', $token);

        $stmt = $this->conn->prepare("UPDATE email_verifications SET used_at = NOW() WHERE token_hash = ?");
        $stmt->execute([$tokenHash]);
    }
}