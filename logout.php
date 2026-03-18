<?php
/**
 * Logout Handler
 * Destroys session and redirects to login
 */

session_start();

// Destroy all session data
$_SESSION = array();

// Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destroy session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
