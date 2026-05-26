<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/Database.php';

class UserDAO {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    private function rowToUser(array $row): User {
        return new User(
            id:            (int)$row['id'],
            username:      $row['username'],
            email:         $row['email'],
            image_id:       isset($row['image_id']) ? (int)$row['image_id'] : null,
            telefone:      $row['telefone'] ?? null,
            password:      $row['password'],
            morada:        $row['morada'] ?? '',
            dt_nascimento: $row['dt_nascimento'] ?? '',
            dt_criacao:    $row['dt_criacao'] ?? '',
            pronomes:      $row['pronomes'] ?? null,
            is_admin:      (bool)$row['is_admin'],
            ultimo_login:  $row['ultimo_login'] ?? '',
            is_verified:   (bool)$row['is_verified'],
            verified_at:   $row['verified_at'] ?? null,
            created_at:    $row['created_at'],
            updated_at:    $row['updated_at'],
            deleted_at:    $row['deleted_at'] ?? null
        );
    }

    public function findById($userId): User|false {
        $sql = "
            SELECT * 
            FROM utilizadores 
            WHERE id = :id
            AND is_verified = 1
            AND verified_at IS NOT NULL
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToUser($row) : false;
    }

    public function findByEmail($email): User|false {
        $sql = "
            SELECT * 
            FROM utilizadores 
            WHERE email = :email
            AND is_verified = 1
            AND verified_at IS NOT NULL
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToUser($row) : false;
    }

    public function updateUser($userId, $username, $email): int {
        $sql = "
            UPDATE utilizadores 
            SET username = ?, email = ?, updated_at = NOW()
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username, $email, $userId]);
        return $stmt->rowCount();
    }

    public function getUsersDao(): array {
        $sql = "
            SELECT 
                utilizadores.id,
                utilizadores.username,
                utilizadores.email,
                utilizadores.image_id,
                utilizadores.telefone,
                utilizadores.password,
                utilizadores.morada,
                utilizadores.dt_nascimento,
                utilizadores.dt_criacao,
                utilizadores.pronomes,
                utilizadores.is_admin,
                utilizadores.ultimo_login,
                utilizadores.is_verified,
                utilizadores.verified_at,
                utilizadores.created_at,
                utilizadores.updated_at,
                utilizadores.deleted_at
            FROM utilizadores 
            WHERE is_verified = 1 AND verified_at IS NOT NULL
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];

        foreach($rows as $row) {
            $users[] = $this->rowToUser($row);

        }
        
        return $users;
    }

    public function createPending($username, $email): int {
        $sql = "
            INSERT INTO utilizadores 
                (username, email, password, is_admin, image_id, morada, dt_nascimento, dt_criacao, pronomes, ultimo_login, is_verified, verified_at, created_at, updated_at, deleted_at)
            VALUES 
                (?, ?, '', 0, NULL, '', NULL, NOW(), NULL, NULL, 0, NULL, NOW(), NOW(), NULL)
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username, $email]);
        return (int)$this->conn->lastInsertId();
    }

    public function setPasswordAndVerify($userId, $passwordHash): void {
        $sql = "
            UPDATE utilizadores
            SET password = ?, 
                is_verified = 1, 
                verified_at = NOW(),
                updated_at = NOW()
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$passwordHash, $userId]);
    }

    public function arrayUsersDAO() {
    $sql = "
        SELECT
            utilizadores.id,
            utilizadores.username,
            utilizadores.email,
            utilizadores.image_id,
            utilizadores.telefone,
            utilizadores.password,
            utilizadores.morada,
            utilizadores.dt_nascimento,
            utilizadores.dt_criacao,
            utilizadores.pronomes,
            utilizadores.is_admin,
            utilizadores.ultimo_login,
            utilizadores.is_verified,
            utilizadores.verified_at,
            utilizadores.created_at,
            utilizadores.updated_at,
            utilizadores.deleted_at 
        FROM utilizadores;
    ";
 
    $stmt = $this->conn->prepare($sql);
 
    $stmt->execute();
 
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    return $rows;
  }

  public function countUsers(){
    $sql = "
        SELECT COUNT(*) as num_users 
        FROM utilizadores;
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
  }

}