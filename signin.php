<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input    = $_POST['login'];      // email or phone
    $password = $_POST['password'];   // plain text password

    // Check in users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='donor_dashboard.php';</script>";
        exit();
    }

    // Check in ngo table
    $stmt = $conn->prepare("SELECT * FROM ngo WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $ngo = $result->fetch_assoc();

    if ($ngo && $password === $ngo['password']) {
        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='ngo_dashboard.php';</script>";
        exit();
    }

    // Check in admin table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_id'] = $admin['id'];       
        $_SESSION['admin_name'] = $admin['admin_name'];
        $_SESSION['user_role'] = 'admin';
        echo "<script>alert('Admin Signin Successful! Welcome back'); window.location.href='admin/admin.php';</script>";
        exit();
    }

    // If no match found
    echo "<script>alert('Invalid login credentials!'); window.location.href='index.html';</script>";
}

$conn->close();
?>
