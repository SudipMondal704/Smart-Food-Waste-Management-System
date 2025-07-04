<?php
require_once('adminSession.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
<body>
<div class="table-container">
<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = mysqli_query($conn, "SELECT * FROM feedback");
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
echo "</tbody></table>";
$conn->close();
?>
</div>

</body>
</html>
