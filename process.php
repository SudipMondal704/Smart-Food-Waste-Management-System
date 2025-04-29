<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_waste"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Start process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "registration") {
        // Registration process
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
                // Registration successful - Redirect back to registration.php with message + redirect to login
                header("Location: registration.php?message=Registration successful!&redirect=login.php");
                exit();
            } else {
                // Registration failed
                header("Location: registration.php?message=Registration failed: " . urlencode($conn->error));
                exit();
            }
        } else {
            // Passwords do not match
            header("Location: registration.php?message=Passwords do not match!");
            exit();
        }

    } elseif ($action == "login") {
        // Login process
        $email_phone = $_POST['email_phone'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $sql = "SELECT * FROM users WHERE email = '$email_phone' OR phone = '$email_phone'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($confirm_password, $user['password'])) {
                // Login successful - Redirect to home page
                header("Location: login.php?message=Login successful!&redirect=Home.php");
                exit();
            } else {
                // Invalid password
                header("Location: login.php?message=Invalid password!");
                exit();
            }
        } else {
            // User not found
            header("Location: login.php?message=User not found!");
            exit();
        }

    } else {
        // Invalid action
        header("Location: registration.php?message=Invalid action specified.");
        exit();
    }

} else {
    // Invalid access
    header("Location: registration.php?message=Invalid access.");
    exit();
}
$conn->close();  
?>
