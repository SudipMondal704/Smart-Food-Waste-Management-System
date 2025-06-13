<?php

// Check if user is logged in as admin
require_once('adminSession.php');


// Database connection
$server = "localhost";
$user = "root";
$password = "";
$database = "food_waste";
$conn = new mysqli($server, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch one row per donation batch (donor + pickup address + NGO combo)
// Each batch can have multiple food items
$sql_assigned = "
    SELECT 
        f.user_id,
        u.username,
        u.address AS user_address,
        f.donor_name,
        f.pickup_address,
        f.assigned_ngo_id,
        n.ngo_name,
        n.address AS ngo_address,
        COUNT(f.food_name) AS total_food_items,
        MAX(f.created_at) AS latest_time
    FROM fooddetails f
    JOIN ngo n ON f.assigned_ngo_id = n.ngo_id
    JOIN users u ON f.user_id = u.user_id
    WHERE f.assigned_ngo_id IS NOT NULL
    GROUP BY f.user_id, f.pickup_address, f.assigned_ngo_id, f.created_at
    ORDER BY latest_time ASC
";
$result_assigned = $conn->query($sql_assigned);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Food Donations</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
<table>
        <thead>
            <tr>
                <th>Donor Information</th>
                <th>Pickup Address</th>
                <th>Assigned NGO</th>
                <th>NGO Address</th>
                <th>Food Items</th>
                <th>Status</th>
                <th>Assignment Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_assigned->num_rows > 0) {
                while ($row = $result_assigned->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<strong>" . htmlspecialchars($row['donor_name']) . "</strong><br>";
                    echo "<small>User: " . htmlspecialchars($row['username']) . "</small>";
                    echo "</td>";
                    echo "<td class='address'>" . htmlspecialchars($row['pickup_address']) . "</td>";
                    echo "<td>";
                    echo "<strong>" . htmlspecialchars($row['ngo_name']) . "</strong><br>";
                    echo "<small>ID: " . htmlspecialchars($row['assigned_ngo_id']) . "</small>";
                    echo "</td>";
                    echo "<td class='address'>" . htmlspecialchars($row['ngo_address']) . "</td>";
                    echo "<td><span class='food-count'>" . $row['total_food_items'] . " items</span></td>";
                    echo "<td><span class='status-badge'>Assigned</span></td>";
                    echo "<td class='timestamp'>" . date('M j, Y g:i A', strtotime($row['latest_time'])) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='no-data'>No assigned food donations found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to rows for potential future functionality
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function() {
                    // Future: Could redirect to detailed view
                    console.log('Row clicked:', this);
                });
            });
            
            // Auto-refresh every 5 minutes
            setTimeout(function() {
                location.reload();
            }, 300000);
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>