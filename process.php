<?php
session_start(); // Start session at the very top before any output

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_waste"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Hardcoded Admin credentials
$admin_credentials = [
    "rayantan@gmail.com" => password_hash("rayantan", PASSWORD_DEFAULT),
    "1111" => password_hash("rayantan", PASSWORD_DEFAULT),
    "sudip@gmail.com" => password_hash("sudip", PASSWORD_DEFAULT),
    "2222" => password_hash("sudip", PASSWORD_DEFAULT),
    "anjan@gmail.com" => password_hash("anjan", PASSWORD_DEFAULT),
    "3333" => password_hash("anjan", PASSWORD_DEFAULT),
    "rintu@gmail.com" => password_hash("rintu", PASSWORD_DEFAULT),
    "4444" => password_hash("rintu", PASSWORD_DEFAULT),
];

// Action Check
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // ---------------- USER REGISTRATION ---------------- //
    if ($action === "registration") {
        $username = $_POST['username'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $gender = $_POST['gender'] ?? '';

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, address, email, phone, password, gender) 
                    VALUES ('$username', '$address', '$email', '$phone', '$hashed_password', '$gender')";

            if ($conn->query($sql) === TRUE) {
                header("Location: registration.php?message=Registration successful!&redirect=login.php");
                exit();
            } else {
                header("Location: registration.php?message=Registration failed: " . urlencode($conn->error));
                exit();
            }
        } else {
            header("Location: registration.php?message=Passwords do not match!");
            exit();
        }

    // ---------------- USER or ADMIN LOGIN ---------------- //
    } elseif ($action === "login") {
        $email_phone = $_POST['email_phone'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // ADMIN login check
        if (array_key_exists($email_phone, $admin_credentials)) {
            if (password_verify($confirm_password, $admin_credentials[$email_phone])) {
                $_SESSION['user_role'] = 'admin';
                $_SESSION['user_email'] = $email_phone;

                header(header:"Location:login.php?message=Welcome Admin!&redirect=admin/admin.php");
                exit();
            } else {
                header("Location: login.php?message=Incorrect admin password!");
                exit();
            }
        }

        // USER login check
        $sql = "SELECT * FROM users WHERE email = '$email_phone' OR phone = '$email_phone'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($confirm_password, $user['password'])) {
                $_SESSION['user_role'] = 'user';
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];

                header("Location: Home.php?message=Login successful!");
                exit();
            } else {
                header("Location: login.php?message=Invalid password!");
                exit();
            }
        } else {
            header("Location: login.php?message=User not found!");
            exit();
        }

    // ---------------- NGO REGISTRATION ---------------- //
    } elseif ($action === "NGOReg") {
        $ngo_name = $_POST['ngo_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO ngo (ngo_name, address, email, phone, password) 
                    VALUES ('$ngo_name', '$address', '$email', '$phone', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: NGOReg.php?message=NGO Registration successful!&redirect=NGOlog.php");
                exit();
            } else {
                header("Location: NGOReg.php?message=NGO Registration failed: " . urlencode($conn->error));
                exit();
            }
        } else {
            header("Location: NGOReg.php?message=Passwords do not match!");
            exit();
        }

    // ---------------- NGO LOGIN ---------------- //
    } elseif ($action === "NGOlog") {
        $email_phone = $_POST['email_phone'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $sql = "SELECT * FROM ngo WHERE email = '$email_phone' OR phone = '$email_phone'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $ngo = $result->fetch_assoc();
            if (password_verify($confirm_password, $ngo['password'])) {
                $_SESSION['user_role'] = 'ngo';
                $_SESSION['user_id'] = $ngo['id'];
                $_SESSION['user_name'] = $ngo['ngo_name'];

                header("Location: Home.php?message=NGO Login successful!");
                exit();
            } else {
                header("Location: NGOlog.php?message=Invalid password!");
                exit();
            }
        } else {
            header("Location: NGOlog.php?message=NGO not found!");
            exit();
        }

    } else {
        header("Location: login.php?message=Invalid action specified.");
        exit();
    }

} else {
    header("Location: login.php?message=Invalid request method.");
    exit();
}

$conn->close();
?>
