<?php
// PHP built-in server routing
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path !== __FILE__ && file_exists($path) && is_file($path)) {
        return false;
    }
}

session_start();
require_once __DIR__ . '/../app/init.php';

$app = new App();
