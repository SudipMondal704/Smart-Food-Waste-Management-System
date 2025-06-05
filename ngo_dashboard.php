<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ngo') {
    echo "<script>alert('Login as NGO!.'); window.location.href='newlogin.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "food_waste");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get NGO name
$ngo_query = $conn->query("SELECT ngo_name FROM ngo WHERE ngo_id = $user_id");
$ngo_name = "";
if ($ngo_query && $ngo_query->num_rows > 0) {
    $row = $ngo_query->fetch_assoc();
    $ngo_name = $row['ngo_name'];
}

// Get assigned food donations
$donations = $conn->query("SELECT * FROM fooddetails WHERE assigned_ngo_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>NGO Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f2f6;
            padding: 20px;
        }
        h2 {
            color: #2f3542;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #ffffff;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ced6e0;
        }
        th {
            background-color: #1e90ff;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Welcome, <?= htmlspecialchars($ngo_name) ?>!</h2>
    <h3>Assigned Food Donations</h3>

    <table>
        <tr>
            <th>Donor Name</th>
            <th>Pickup Address</th>
            <th>Food Name</th>
            <th>Quantity</th>
            <th>Unit</th>
        </tr>
        <?php
        if ($donations->num_rows > 0) {
            while ($donation = $donations->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($donation['donor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($donation['pickup_address']) . "</td>";
                echo "<td>" . htmlspecialchars($donation['food_name']) . "</td>";
                echo "<td>" . htmlspecialchars($donation['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($donation['unit']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No donations assigned yet.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
