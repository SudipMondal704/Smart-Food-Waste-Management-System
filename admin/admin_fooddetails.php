<!DOCTYPE html>
<html>
<head>
    <title>Food Details Admin Panel</title>
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
echo "
<table>
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

while ($row = mysqli_fetch_assoc($result)) {
    $imagePath = htmlspecialchars($row['image']);

    // Ensure the path uses forward slashes
    $imageURL = !empty($imagePath) ? str_replace('\\', '/', $imagePath) : "";

    // Check if file exists
    if (!empty($imageURL) && file_exists($imageURL)) {
    $imageTag = "<img src='$imageURL' alt='Food Image' style='width: 75px; height: 75px; object-fit: cover; border-radius: 0;'>";


    } else {
        $imageTag = "No Image";
    }

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
 "</table>";
}
//echo "</table>";

// Close connection
$conn->close();
?>

</body>
</html>
