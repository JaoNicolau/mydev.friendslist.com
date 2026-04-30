<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../config/Database.php";

class UserDAO{

  private $conn;

  public function __construct()
  {
    // Conexão à base de dados
    $this->conn = (new Database())->connect();
  }

  public function findByEmail($email)
  {
    $sql = "SELECT * FROM users WHERE email = :email";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindParam(':email', $email);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row) {
      $user = new User(
        (int)$row["id"],
        (string)$row["username"],
        (string)$row["email"],
        (string)$row["password"],
        (bool)$row["is_admin"],
        $row["created_at"],
        $row["updated_at"],
        $row["deleted_at"]
      );

      return $user;
    } else {
      return false;
    }

    
  }

  public function createPending($username, $email) {
    $sql = "
    INSERT INTO users (username, email, password, is_admin, is_verified, verified_at, created_at, updated_at, deleted_at)
    VALUES (?, ?, '', 0, 0, NULL, NOW(), NOW(), NULL)
  ";
 
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$username, $email]);
 
    return (int)$this->conn->lastInsertId();
  }

  public function getAllUsers() {
    $sql = "SELECT * FROM users";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function countUsers() {
    $sql = "SELECT COUNT(*) as count FROM users";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int)$row['count'];
  }

  public function setPasswordAndVerify($userId, $passwordHash) {
    $sql = "
      UPDATE users 
      SET password = ?, 
      is_verified = 1, 
      verified_at = NOW() 
      WHERE id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$passwordHash, $userId]);
  }

  
}