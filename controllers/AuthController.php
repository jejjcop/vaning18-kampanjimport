<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private UserModel $userModel;

    public function __construct(PDO $pdo) 
    {
        $this->userModel = new UserModel($pdo);
    }

    public function showLogin(): void 
    {
        require_once __DIR__ . '/../views/login_view.php';
    }

    public function login(): void 
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->authenticate($username, $password);

        if ($user) 
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } 
        else 
        {
            $errorMessage = "Invalid username or password";
            require_once __DIR__ . '/../views/login_view.php';
        }
    }

    public function logout(): void 
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
