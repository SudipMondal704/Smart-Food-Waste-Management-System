<?php
// Database connection
$server = "localhost";
$user = "root";
$password = "";
$database = "food_waste";

$conn = new mysqli($server, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get assigned food donations with NGO info
$sql_assigned = "SELECT f.*, n.ngo_name, n.address AS ngo_address 
                 FROM fooddetails f 
                 JOIN ngo n ON f.assigned_ngo_id = n.ngo_id";
$result_assigned = $conn->query($sql_assigned);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assigned Requests</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: none; /* Removed all borders */
        }
        th {
            background: #343a40;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Donor Name</th>
            <th>Pickup Address</th>
            <th>Food Name</th>
            <th>Quantity</th>
            <th>Assigned NGO</th>
            <th>NGO Address</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_assigned->num_rows > 0) {
            while ($row = $result_assigned->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pickup_address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['food_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity'] . " " . $row['unit']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ngo_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ngo_address']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No assigned food requests found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
