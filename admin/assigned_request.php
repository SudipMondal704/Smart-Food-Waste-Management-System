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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 0px 30px 30px 30px;
            margin: 0;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        
        h2 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
           background: linear-gradient(135deg,rgb(9, 3, 94));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .food-count {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .address {
            max-width: 200px;
            word-wrap: break-word;
        }
        
        .timestamp {
            color: #666;
            font-size: 0.9em;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            table {
                font-size: 0.9em;
            }
            
            th, td {
                padding: 8px;
            }
            
            .address {
                max-width: 120px;
            }
        }
    </style>
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