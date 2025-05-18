<!DOCTYPE html>
<html>
<head>
<title>Feedback Admin Panel</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
  <?php
    //include('header.php');
// database connection
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
<tr>
<th>Feedback ID</th>
<th> Name</th>
<th>Email</th>
<th>Phone</th>
<th>Feedback</th>
<th>Feedback Record</th>

</tr>";
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
echo "</table>";
?>
