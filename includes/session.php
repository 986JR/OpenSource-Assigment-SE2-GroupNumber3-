<?php
// Start a session once for pages that need user login information.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ../auth/login.php');
        exit;
    }
}
