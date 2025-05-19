<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding:0px 30px 30px 30px; /* Reduced top padding */
            margin: 0;
        }
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            margin: 10px auto;
            width: 95%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background-color: #343a40;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="table-container">
<?php
// database connection
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = mysqli_query($conn, "SELECT * FROM users");

echo "<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Address</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Gender</th>
    <th>Registration Record</th>
</tr>
</thead>
<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['user_id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['address']}</td>
        <td>{$row['email']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['gender']}</td>
        <td>{$row['created_at']}</td>
    </tr>";
}

echo "</tbody></table>";
$conn->close();
?>
</div>

</body>
</html>
