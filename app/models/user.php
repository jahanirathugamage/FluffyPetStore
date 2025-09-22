<?php
require_once(__DIR__ . "/Database.php");

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Get user by email
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE userEmail = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create user account
    public function createUser($name, $email, $password, $role = 'Customer') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO user (userName, userEmail, userPassword, userRole) 
             VALUES (:name, :email, :password, :role)"
        );
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Get user by ID
    public function getUserById($userID) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE userID = :id");
        $stmt->bindParam(':id', $userID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // create an employee account and insert it to the db
    public function createEmployee($name, $email, $password, $role = 'Customer') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO user (userName, userEmail, userPassword, userRole) 
            VALUES (:name, :email, :password, :role)"
        );
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>