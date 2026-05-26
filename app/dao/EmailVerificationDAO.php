<?php

class EmailVerificationDAO {
    private $conn;

    public function __construct() {
        // Conectar à base de dados
        $this->conn = (new Database())->connect();
    }

    public function createForUser($userId, $ttlSeconds = 300) {
        $token = bin2hex(random_bytes(32) . round(microtime(true)) * 1000000);
        $tokenHash = hash('sha256', $token);

        $sql = "
            INSERT INTO email_verifications (user_id, token_hash, expires_at, used_at, created_at)
            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NULL, NOW())
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $tokenHash, $ttlSeconds]);
    
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
    
        $userId = $stmt->fetchColumn();

        return $userId ? (int)$userId : false;
    }
    
    public function markUsed(string $token): void
    {
        $tokenHash = hash('sha256', $token);
    
        $stmt = $this->conn->prepare("UPDATE email_verifications SET used_at = NOW() WHERE token_hash = ?");
        $stmt->execute([$tokenHash]);
    }

    public function getVerificationsByUserId($userId): array {
        $sql = "
            SELECT 
                email_verifications.id,
                email_verifications.user_id,
                email_verifications.token_hash,
                email_verifications.expires_at,
                email_verifications.used_at,
                email_verifications.created_at
            FROM email_verifications
            WHERE user_id = ?;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        return $rows;
    }

    public function countVerifications() {
        $sql = "
            SELECT COUNT(*) 
            FROM email_verifications
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
}