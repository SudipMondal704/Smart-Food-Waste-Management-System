<!DOCTYPE html>
<html>
<head>
    <title>Feedback Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
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

// Fetch all food details
$result = mysqli_query($conn, "SELECT * FROM fooddetails");

// HTML table structure
echo "<table>
<tr>
    <th>Food ID</th>
    <th>Donor<br> Name</th>
    <th>Pickup <br>Address</th>
    <th>Phone<br> Number</th>
    <th>Food<br> Name</th>
    <th>Food <br>Type</th>
    <th>Quantity</th>
    <th>Unit</th>
    <th>Image</th>
    <th>Date & <br>Time</th>
</tr>";

// Loop through the result set
while ($row = mysqli_fetch_assoc($result)) {
    // Convert image BLOB to base64 string
    $imageData = base64_encode($row['image']);
    $imageTag = '<img src="data:image/jpeg;base64,' . $imageData . '"style="width: 75px; height: 75px; border-radius: 0;"/>';

    echo "<tr>
        <td>{$row['fooddetails_id']}</td>
        <td>{$row['donor_name']}</td>
        <td>{$row['pickup_address']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['food_name']}</td>
        <td>{$row['food_type']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['unit']}</td>
        <td>$imageTag</td>
        <td>{$row['created_at']}</td>
    </tr>";
}
echo "</table>";

// Close connection
$conn->close();
?>
</body>
</html>
