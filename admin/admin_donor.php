
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
<tr>
<th>ID</th>
<th>Name</th>
<th>Address</th>
<th>Email</th>
<th>Phone</th>
<th>Gender</th>
<th>Registration Record</th>

</tr>";
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
echo "</table>";
?>
