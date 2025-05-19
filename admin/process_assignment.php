<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

// Connect to database
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = $_POST['food_id'];
    $ngo_id = $_POST['ngo_id'];

    $update = "UPDATE fooddetails SET assigned_ngo_id = '$ngo_id' WHERE fooddetails_id = '$food_id'";
    
    if (mysqli_query($conn, $update)) {
        echo "NGO assigned successfully.";
    } else {
        echo "Failed to assign NGO: " . mysqli_error($conn);
    }

    echo "<br><a href='assign_ngo.php'>Go back</a>";
}
?>
