<?php
require_once __DIR__ . '/core/Cache.php';
require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/RouterosAPI.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/config/config.php';

// Secure Session Configuration (after Security is loaded)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
