<?php
require_once(__DIR__ . "/../models/user.php");

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // registration
    public function register($name, $email, $password) {
        $name = trim($name);
        $email = trim($email);
        $password = trim($password);

        // Name validation
        if (empty($name)) return ['error' => 'Please enter your name!'];
        if (preg_match('/[^a-zA-Z0-9 ]/', $name)) return ['error' => 'Name must not contain special characters!'];

        // Email validation
        if (empty($email)) return ['error' => 'Please enter your email!'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['error' => 'Please enter a valid email!'];
        if ($this->userModel->getUserByEmail($email)) return ['error' => 'User already exists!'];

        // Password validation
        if (empty($password)) return ['error' => 'Please enter a password!'];
        if (strlen($password) < 8) return ['error' => 'Password must be 8 characters minimum!'];

        // Insert user
        $this->userModel->createUser($name, $email, $password, 'Customer');

        header("Location: /FluffyPetStore/public/login.php");
        exit();
    }

    // Logging in
    public function login($email, $password) {
        $email = trim($email);
        $password = trim($password);

        // Email validation
        if (empty($email)) return ['error' => 'Please enter your email!'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['error' => 'Please enter a valid email!'];

        // Password validation
        if (empty($password)) return ['error' => 'Please enter a password!'];

        $user = $this->userModel->getUserByEmail($email);
        if (!$user) return ['error' => 'Invalid Email!'];

        if (!password_verify($password, $user['userPassword'])) {
            return ['error' => 'Invalid Password!'];
        }

        // Login successful
        $_SESSION['userID'] = $user['userID'];      
        $_SESSION['userRole'] = $user['userRole'];


        header("Location: /FluffyPetStore/public/index.php");
        exit();
    }

    // logging out
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: /FluffyPetStore/public/login.php");
        exit();
    }

    // Get user info by ID
    public function getUserById($userID) {
        return $this->userModel->getUserById($userID); // Returns associative array of user info
    }

    // Register employee for existing employees to create a new employee account for new employees
    public function registerEmployee($name, $email, $password, $role = 'Employee') {
        // Validate role
        if ($role !== 'Employee') return ['error' => 'Invalid role!'];

        $name = trim($name);
        $email = trim($email);
        $password = trim($password);

        // Name validation
        if (empty($name)) return ['error' => 'Please enter your name!'];
        if (preg_match('/[^a-zA-Z0-9 ]/', $name)) return ['error' => 'Name must not contain special characters!'];

        // Email validation
        if (empty($email)) return ['error' => 'Please enter your email!'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['error' => 'Please enter a valid email!'];
        if ($this->userModel->getUserByEmail($email)) return ['error' => 'User already exists!'];

        // Password validation
        if (empty($password)) return ['error' => 'Please enter a password!'];
        if (strlen($password) < 8) return ['error' => 'Password must be 8 characters minimum!'];

        // Create Employee account
        $this->userModel->createEmployee($name, $email, $password, $role);

        return ['success' => true];
    }
}
?>