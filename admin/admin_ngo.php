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

$result = mysqli_query($conn, "SELECT * FROM ngo");
echo "<table>
<tr>
<th>ID</th>
<th>Organization Name</th>
<th>Address</th>
<th>Email</th>
<th>Phone</th>
<th>Registration Record</th>

</tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['ngo_id']}</td>
        <td>{$row['ngo_name']}</td>
        <td>{$row['address']}</td>
        <td>{$row['email']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['created_at']}</td>
    </tr>";
}
echo "</table>";
?>
