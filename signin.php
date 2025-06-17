<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input    = trim($_POST['login']);    // email or phone
    $password = trim($_POST['password']); // plain text password

    // --------- Check Donor (users table) ---------
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['user_name']  = $user['username'];
        $_SESSION['user_type']  = 'Donor';

        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='home/homeSession.php';</script>";
        exit();
    }

    // --------- Check NGO ---------
    $stmt = $conn->prepare("SELECT * FROM ngo WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $ngo = $result->fetch_assoc();

    if ($ngo && $password === $ngo['password']) {
        $_SESSION['user_id']    = $ngo['ngo_id'];
        $_SESSION['user_name']  = $ngo['ngo_name'];
        $_SESSION['user_type']  = 'NGO';
         $_SESSION['role']     = 'ngo';

        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='home/homeSession.php';</script>";
        exit();
    }

    // --------- Check Admin ---------
     $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $password === $admin['password']) {
        // Fixed: Using $admin instead of $row
        $_SESSION['user_role']     = 'admin';
        $_SESSION['user_id']       = $admin['id'];
        $_SESSION['admin_name']    = $admin['admin_name'];
        $_SESSION['admin_email']   = $admin['admin_email'];
        $_SESSION['admin_phone']   = $admin['phone'];
        echo "<script>alert('Admin Signin Successful!'); window.location.href='admin/admin.php';</script>";
        exit();
    }

    // --------- No match found ---------
    echo "<script>alert('Invalid login credentials!'); window.location.href='home/homeSession.php';</script>";
}

$conn->close();
?>
