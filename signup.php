<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $name     = $_POST['username'];
    $type     = $_POST['acc-type'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $password = $_POST['password'];

    if ($type == "Donor") {
        // Insert into users (Donor) table
        $stmt = $conn->prepare("INSERT INTO users (username, address, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $address, $email, $phone, $password);

    } elseif ($type == "NGO") {
        // Insert into NGO table
        $stmt = $conn->prepare("INSERT INTO ngo (ngo_name, address, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $address, $email, $phone, $password);

    } else {
        die("Invalid account type.");
    }

    // Execute and check result
    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login_signup.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
