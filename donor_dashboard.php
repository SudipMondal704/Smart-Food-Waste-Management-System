<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location:signup.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "food_waste");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donor name
$name_result = $conn->query("SELECT username FROM users WHERE user_id = $user_id");
$donor_name = "";
if ($name_result && $name_result->num_rows > 0) {
    $donor_row = $name_result->fetch_assoc();
    $donor_name = $donor_row['username'];
}

// Fetch donations
$donations = $conn->query("SELECT * FROM fooddetails WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donor Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }
        h2 {
            color: #2f3542;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #3742fa;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Welcome, <?= htmlspecialchars($donor_name) ?>!</h2>
    <h3>Your Food Donations</h3>

    <table>
        <tr>
            <th>Food Name</th>
            <th>Quantity</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $donations->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['food_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity'] . " " . $row['unit']) ?></td>
                <td><?= $row['assigned_ngo_id'] ? "Assigned" : "Pending" ?></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
