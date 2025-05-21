<!DOCTYPE html>
<html>
<head>
    <title>Food Details Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 0px 30px 30px 30px;
            margin: 0;
        }
        .table-container {
            overflow-x: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            margin: 10px auto;
            width: 95%;
        }
        table {
            width: 100%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 18px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #343a40;
            color: #fff;
        }
        
        img {
            width: 75px;
            height: 75px;
            object-fit: cover;
        }
        .not-assigned {
            color: red;
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

// Fetch all food details and join with NGO info
$query = "
    SELECT f.*, n.ngo_name 
    FROM fooddetails f 
    LEFT JOIN ngo n ON f.assigned_ngo_id = n.ngo_id
";
$result = mysqli_query($conn, $query);

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
    <th>Image</th>
    <th>Date & <br>Time</th>
    <th>Assigned<br> NGO</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    // Extract image filename
    $storedPath = htmlspecialchars($row['image']); // e.g. admin/uploads/filename.jpg or uploads/filename.jpg
    $filename = basename($storedPath); // e.g. filename.jpg

    // Paths for checking and displaying image
    $filePath = __DIR__ . "/uploads/" . $filename;
    $imgURL = "uploads/" . $filename;

    if (file_exists($filePath)) {
    $imageTag = "<img src='$imgURL' alt='Food Image' style='width: 75px; height: 75px; object-fit: cover; border: 1px solid #ccc; border-radius:0px;'>";

    } else {
        $imageTag = "No Image";
    }

    $ngoName = $row['ngo_name'] ? $row['ngo_name'] : "<span class='not-assigned'>Not Assigned</span>";
    
    // Combine quantity and unit
    $quantityUnit = $row['quantity'] . $row['unit'];

    echo "<tr>
        <td>{$row['fooddetails_id']}</td>
        <td>{$row['donor_name']}</td>
        <td>{$row['pickup_address']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['food_name']}</td>
        <td>{$row['food_type']}</td>
        <td>$quantityUnit</td>
        <td>$imageTag</td>
        <td>{$row['created_at']}</td>
        <td>$ngoName</td>
    </tr>";
}
echo "</table>";

$conn->close();
?>
</div>

</body>
</html>