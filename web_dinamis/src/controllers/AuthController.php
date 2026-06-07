<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getByUsername($username);

            if ($user && md5($password) === $user['password']) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: /');
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        }

        require_once 'views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
