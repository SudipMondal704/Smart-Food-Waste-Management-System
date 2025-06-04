<!DOCTYPE html>
<html>
<head>
    <title>Feedback Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 0px 30px 30px 30px;
            margin: 0;
        }
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            margin: 10px auto; /* Reduced margin to shift table up */
            width: 95%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="table-container">
<?php
// Database connection
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch feedback data
$result = mysqli_query($conn, "SELECT * FROM feedback");

// Start table
echo "<table>
<thead>
<tr>
    <th>Feedback ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Feedback</th>
    <th>Feedback Record</th>
</tr>
</thead>
<tbody>";

// Table rows from database
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['feedback_id']}</td>
        <td>{$row['full_name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['phone_number']}</td>
        <td>{$row['feedback_text']}</td>
        <td>{$row['feedback_date']}</td>
    </tr>";
}

// Close table
echo "</tbody></table>";

// Close connection
$conn->close();
?>
</div>

</body>
</html>
