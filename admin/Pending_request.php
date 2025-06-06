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

$message = "";

// Handle NGO assignment to all unassigned foods of the donor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['ngo_id'])) {
    $donor_id = $_POST['user_id'];
    $ngo_id = $_POST['ngo_id'];
    $pickup_address = $_POST['pickup_address'];

    $update_sql = "UPDATE fooddetails SET assigned_ngo_id = $ngo_id WHERE user_id = $donor_id AND pickup_address = '$pickup_address' AND assigned_ngo_id IS NULL";
    if ($conn->query($update_sql)) {
        $message = "NGO assigned to all pending foods of the donor at this address.";
    } else {
        $message = "Failed to assign NGO.";
    }
}

// Get all unassigned donor details (one row per donor per address)
$sql_unassigned_donors = "
    SELECT f.user_id, f.donor_name, f.pickup_address, u.username, u.address as user_address 
    FROM fooddetails f 
    JOIN users u ON f.user_id = u.user_id 
    WHERE f.assigned_ngo_id IS NULL 
    GROUP BY f.user_id, f.pickup_address";
$result_unassigned = $conn->query($sql_unassigned_donors);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Food Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 0px 30px 30px 30px;
        }
        h2 {
            color: #333;
        }
        .message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
          background: linear-gradient(135deg,rgb(9, 3, 94));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        select, button {
            padding: 6px;
            font-size: 14px;
        }
        button {
            background-color: green;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkgreen;
        }
        form {
            margin: 0;
        }
    </style>
</head>
<body>

<?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Username (Phone)</th>
                <th>User Address</th>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Select NGO</th>
                <th>Assign</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_unassigned->num_rows > 0) {
                while ($row = $result_unassigned->fetch_assoc()) {
                    $donor_id = $row['user_id'];
                    $donor_name = htmlspecialchars($row['donor_name']);
                    $pickup_address = htmlspecialchars($row['pickup_address']);
                    $username = htmlspecialchars($row['username']);
                    $user_address = htmlspecialchars($row['user_address']);

                    echo "<tr>";
                    echo "<form method='POST'>";
                    echo "<td>$username</td>";
                    echo "<td>$user_address</td>";
                    echo "<td>$donor_name</td>";
                    echo "<td>$pickup_address</td>";
                    echo "<td>";
                    echo "<select name='ngo_id' required>";
                    echo "<option value=''>-- Select NGO --</option>";

                    // Fetch NGOs with the same pickup address from fooddetails table
                    $addr_safe = $conn->real_escape_string($pickup_address);
                    $ngo_sql = "SELECT DISTINCT n.* FROM ngo n 
                               JOIN fooddetails f ON LOWER(n.address) = LOWER('$addr_safe') 
                               UNION 
                               SELECT * FROM ngo WHERE LOWER(address) = LOWER('$addr_safe')";
                    $ngo_result = $conn->query($ngo_sql);

                    if ($ngo_result && $ngo_result->num_rows > 0) {
                        while ($ngo = $ngo_result->fetch_assoc()) {
                            $ngo_id = $ngo['ngo_id'];
                            $ngo_name = htmlspecialchars($ngo['ngo_name']);
                            $ngo_address = htmlspecialchars($ngo['address']);
                            echo "<option value='$ngo_id'>$ngo_name ($ngo_address)</option>";
                        }
                    } else {
                        echo "<option value=''>No NGO found for this pickup address</option>";
                    }

                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<input type='hidden' name='user_id' value='$donor_id'>";
                    echo "<input type='hidden' name='pickup_address' value='$pickup_address'>";
                    echo "<button type='submit'>Assign</button>";
                    echo "</td>";
                    echo "</form>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No pending food requests found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>