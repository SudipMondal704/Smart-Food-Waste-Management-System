<?php
/**
 * Admin Session Management
 * Include this file at the top of any admin PHP file
 * Usage: require_once('admin_session.php');
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Login as Admin!'); window.location.href='../newlogin.php';</script>";
    exit();
}

// Optional: Set session timeout (30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // Session expired
    session_unset();
    session_destroy();
    echo "<script>alert('Session expired. Please login again.'); window.location.href='../newlogin.php';</script>";
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Helper functions for admin pages
function getAdminName() {
    return $_SESSION['username'] ?? 'Admin';
}

function getAdminId() {
    return $_SESSION['user_id'] ?? null;
}

function isValidAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Optional: Log admin activity (you can expand this)
function logAdminActivity($action, $page) {
    $log_entry = date('Y-m-d H:i:s') . " - Admin: " . getAdminName() . " - Action: $action - Page: $page\n";
    // You can write to a log file or database here
    // file_put_contents('admin_logs.txt', $log_entry, FILE_APPEND | LOCK_EX);
}
?>