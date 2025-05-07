<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_waste";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, email, phone, address, gender FROM ngo ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
       echo "<table border='1' style='width:100%; border-collapse: collapse;'>
            <tr>
                <th>ID</th>
                <th>NGO Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['ngo_name']}</td>
                <td>{$row['address']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}
$conn->close();
?>
