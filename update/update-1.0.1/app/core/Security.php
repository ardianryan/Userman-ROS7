<?php

class Security {
    /** Sanitizes any input (string or array) */
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } elseif (is_string($data)) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    /** CSRF Token Generation */
    public static function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** CSRF Verification */
    public static function verify_csrf($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
