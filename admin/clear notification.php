<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo 'unauthorized';
    exit;
}

// Check if clear_all request is made
if (isset($_POST['clear_all']) && $_POST['clear_all'] == '1') {
    try {
        // Include your database connection file
        // require_once 'db_connection.php'; // Uncomment and adjust path as needed
        
        // Example database operation to mark notifications as read/cleared
        // Adjust the query based on your database structure
        
        // Option 1: Mark all notifications as read for this admin
        // $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE admin_id = ? OR admin_id IS NULL");
        // $stmt->execute([$_SESSION['admin_id']]);
        
        // Option 2: Delete all notifications for this admin
        // $stmt = $pdo->prepare("DELETE FROM notifications WHERE admin_id = ? OR admin_id IS NULL");
        // $stmt->execute([$_SESSION['admin_id']]);
        
        // Option 3: Add a timestamp to track when notifications were last cleared
        // $stmt = $pdo->prepare("UPDATE admins SET notifications_cleared_at = NOW() WHERE id = ?");
        // $stmt->execute([$_SESSION['admin_id']]);
        
        // For now, we'll use session to track cleared notifications
        $_SESSION['notifications_cleared_at'] = time();
        
        echo 'success';
        
    } catch (Exception $e) {
        http_response_code(500);
        echo 'error';
        error_log("Clear notifications error: " . $e->getMessage());
    }
} else {
    http_response_code(400);
    echo 'invalid_request';
}

?>