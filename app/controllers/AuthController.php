<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController
{
    private $userModel;

    public function __construct(\mysqli $conn)
    {
        $this->userModel = new UserModel($conn);
    }

    // Show register page
    public function register()
    {
        require_once __DIR__ . '/../views/register.php';
    }

    // Process registration
    public function registerProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=register");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid name and email.";
            header("Location: index.php?action=register");
            exit;
        }

        if ($password === '') {
            $_SESSION['error'] = "Password is required.";
            header("Location: index.php?action=register");
            exit;
        }

        // Check password
        if ($password !== $confirmPassword) {

            $_SESSION['error'] = "Password does not match.";

            header("Location: index.php?action=register");
            exit;
        }

        // Save user
        $result = $this->userModel->registerUser(
            $name,
            $email,
            $password
        );

        if ($result) {

            $_SESSION['success'] =
                "Registration successful. Please login.";

            header("Location: index.php?action=login");
            exit;

        } else {

            $_SESSION['error'] =
                "Email already exists.";

            header("Location: index.php?action=register");
            exit;
        }
    }

    // Show login page
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: index.php?action=login");
            exit;
        }

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: index.php?action=dashboard");
        exit;
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}