<?php

class Controller {
    protected function csrf_check() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!Security::verify_csrf($token)) {
                die('CSRF validation failed.');
            }
        }
    }

    public function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model;
    }
    
    public function redirect($url) {
        header('Location: ' . BASEURL . '/' . $url);
        exit;
    }
}
