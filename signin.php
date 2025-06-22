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
    $input    = trim($_POST['login']); 
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['user_name']  = $user['username'];
        $_SESSION['user_type']  = 'Donor';
         $notification_query = "INSERT INTO notification SET 
                          title='DONOR Login', 
                          details='" . mysqli_real_escape_string($conn, $user['username']) . " has loggedin into the portal',
                          date='" . date('Y-m-d') . "',
                          time='" . date('H:i:s') . "'";
    
    $res = mysqli_query($conn, $notification_query);

        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='home/homeSession.php';</script>";
        exit();
    }

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
         $notification_query = "INSERT INTO notification SET 
                          title='NGO Login', 
                          details='" . mysqli_real_escape_string($conn, $ngo['ngo_name']) . " has logged into the portal',
                          date='" . date('Y-m-d') . "',
                          time='" . date('H:i:s') . "'";
    
    $res = mysqli_query($conn, $notification_query);

        echo "<script>alert('Signin Successful! Welcome back'); window.location.href='home/homeSession.php';</script>";
        exit();
    }

     $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $password === $admin['password']) {
        $_SESSION['user_role']     = 'admin';
        $_SESSION['user_id']       = $admin['id'];
        $_SESSION['admin_name']    = $admin['admin_name'];
        $_SESSION['admin_email']   = $admin['admin_email'];
        $_SESSION['admin_phone']   = $admin['phone'];
        echo "<script>alert('Admin Signin Successful!'); window.location.href='admin/admin.php';</script>";
        exit();
    }

    echo "<script>alert('Invalid login credentials!'); window.location.href='home/homeSession.php';</script>";
}

$conn->close();
?>