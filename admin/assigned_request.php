<?php
// Database connection
$server = "localhost";
$user = "root";
$password = "";
$database = "food_waste";

$conn = new mysqli($server, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch one row per donation batch (donor + address + NGO combo)
// Each batch can have multiple food items
$sql_assigned = "
    SELECT 
        f.user_id,
        f.donor_name,
        f.pickup_address,
        f.assigned_ngo_id,
        n.ngo_name,
        n.address AS ngo_address,
        COUNT(f.food_name) AS total_food_items,
        MAX(f.created_at) AS latest_time -- optional, if you want to show newest donation first
    FROM fooddetails f
    JOIN ngo n ON f.assigned_ngo_id = n.ngo_id
    WHERE f.assigned_ngo_id IS NOT NULL
    GROUP BY f.user_id, f.pickup_address, f.assigned_ngo_id, f.created_at
    ORDER BY latest_time ASC
";

$result_assigned = $conn->query($sql_assigned);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8" />
<head>
    <title>Assigned Food Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 0px 30px 30px 30px;
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #343a40;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>

    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Number of Food Items</th>
                <th>Assigned NGO</th>
                <th>NGO Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_assigned && $result_assigned->num_rows > 0) {
                while ($row = $result_assigned->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['pickup_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_food_items']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ngo_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ngo_address']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-data'>No assigned food donations found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
