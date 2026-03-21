<?php

class Auth extends Controller {

    private function getSettings() {
        return $this->model('SettingsModel')->getAll();
    }

    public function index() {
        if (isset($_SESSION['user_role'])) {
            $this->redirect($_SESSION['user_role'] === 'admin' ? 'admin' : 'user');
        }

        $data['title'] = 'Admin Login';
        $data['settings'] = $this->getSettings();
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth');
        }
        $this->csrf_check();
        $_POST = Security::sanitize($_POST);

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $data['title'] = 'Admin Login';
        $data['settings'] = $this->getSettings();

        try {
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByUsername($username);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $this->redirect($user['role'] === 'admin' ? 'admin' : 'user');
                } else {
                    $data['error'] = 'Invalid password.';
                }
            } else {
                $data['error'] = 'Admin user not found.';
            }
        } catch (Exception $e) {
            $data['error'] = 'Database Error: ' . $e->getMessage();
        }

        if (isset($data['error'])) {
            $this->view('templates/header', $data);
            $this->view('auth/login', $data);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth');
    }
}
