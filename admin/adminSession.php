<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Login as Admin!'); window.location.href='../newlogin.php';</script>";
    exit();
}
$session_timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    session_unset();
    session_destroy();
    echo "<script>alert('Session expired. Please login again.'); window.location.href='../newlogin.php';</script>";
    exit();
}
$_SESSION['last_activity'] = time();
function getAdminName() {
    return $_SESSION['username'] ?? 'Admin';
}
function getAdminId() {
    return $_SESSION['user_id'] ?? null;
}function isValidAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
function logAdminActivity($action, $page) {
    $log_entry = date('Y-m-d H:i:s') . " - Admin: " . getAdminName() . " - Action: $action - Page: $page\n";
}
?>