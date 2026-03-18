<?php
/**
 * Authentication Middleware
 * Protects admin pages by checking for valid session
 */

session_start();

// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Check if user is authenticated
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Check authentication
if (!isAuthenticated()) {
    // Redirect to login page
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: {$protocol}://{$host}{$uri}/login.php");
    exit;
}

// Optional: Check session timeout
if (isset($_ENV['SESSION_TIMEOUT']) && isset($_SESSION['last_activity'])) {
    $timeout = (int)$_ENV['SESSION_TIMEOUT'];
    if (time() - $_SESSION['last_activity'] > $timeout) {
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();
