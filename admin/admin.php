<?php

session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Login.php?message=Please login as admin.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
	<!-- Custom CSS -->
	<link rel="stylesheet" href="admin.css">

	<title>Admin Panel</title>
</head>
<body>



<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "food_waste";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    switch ($type) {
        case 'users':
            $sql = "SELECT user_id, username, address, email, phone, gender, created_at FROM users";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>User List</h3></div><table><thead><tr><th>User ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone</th><th>Gender</th><th>Registered At</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['user_id']}</td><td>{$row['username']}</td><td>{$row['address']}</td><td>{$row['email']}</td><td>{$row['phone']}</td><td>{$row['gender']}</td><td>{$row['created_at']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No users found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        case 'ngo':
            $sql = "SELECT ngo_id, ngo_name, address, email, phone, created_at FROM ngo";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>NGO List</h3></div><table><thead><tr><th>NGO ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone</th><th>Registered At</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['ngo_id']}</td><td>{$row['ngo_name']}</td><td>{$row['address']}</td><td>{$row['email']}</td><td>{$row['phone']}</td><td>{$row['created_at']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No NGOs found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        case 'feedback':
            $sql = "SELECT feedback_id, user_id, full_name, email, phone_number, feedback_text, feedback_date FROM feedback";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>Feedback List</h3></div><table><thead><tr><th>ID</th><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Feedback</th><th>Date</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['feedback_id']}</td><td>{$row['user_id']}</td><td>{$row['full_name']}</td><td>{$row['email']}</td><td>{$row['phone_number']}</td><td>{$row['feedback_text']}</td><td>{$row['feedback_date']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No feedback found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        default:
            echo "<p>Invalid section selected.</p>";
    }
}
?>
		

	<script src="script.js"></script>
</body>
</html>
<?php
    include('header.php');
        
    include('footer.php');
?>

