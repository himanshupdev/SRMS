<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/xml_db.php";

// Check if user is logged in
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Require admin role
function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
}

// Require student role
function require_student() {
    require_login();
    if ($_SESSION['role'] !== 'student') {
        header("Location: index.php");
        exit;
    }
}

// Get current user's role
function get_user_role() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check if user is student
function is_student() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}
?>
