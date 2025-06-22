<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo 'unauthorized';
    exit;
}

// Check if request is POST and has clear_all parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_all'])) {
    
    try {
        // Include your database connection file
        // Replace this with your actual database connection
        include_once 'config.php'; // or whatever your DB connection file is named
        
        // Clear all notifications for this admin
        // Adjust the table name and column names according to your database schema
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE admin_id = ? OR admin_id IS NULL");
        $stmt->execute([$_SESSION['admin_id'] ?? 0]);
        
        // Alternative: If you want to delete notifications instead of marking as read
        // $stmt = $pdo->prepare("DELETE FROM notifications WHERE admin_id = ? OR admin_id IS NULL");
        // $stmt->execute([$_SESSION['admin_id'] ?? 0]);
        
        echo 'success';
        
    } catch (PDOException $e) {
        error_log("Database error in clearNotifications.php: " . $e->getMessage());
        http_response_code(500);
        echo 'database_error';
    } catch (Exception $e) {
        error_log("General error in clearNotifications.php: " . $e->getMessage());
        http_response_code(500);
        echo 'error';
    }
    
} else {
    http_response_code(400);
    echo 'invalid_request';
}
?>