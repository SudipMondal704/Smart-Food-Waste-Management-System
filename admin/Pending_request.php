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

// Handle NGO assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['food_id']) && isset($_POST['ngo_id'])) {
    $food_id = $_POST['food_id'];
    $ngo_id = $_POST['ngo_id'];

    $update_sql = "UPDATE fooddetails SET assigned_ngo_id = $ngo_id WHERE fooddetails_id = $food_id";
    if ($conn->query($update_sql)) {
        $message = "NGO assigned successfully.";
    } else {
        $message = "Failed to assign NGO.";
    }
}

// Get unassigned food donations
$sql_unassigned = "SELECT * FROM fooddetails WHERE assigned_ngo_id IS NULL";
$result_unassigned = $conn->query($sql_unassigned);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Food Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
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
        }
        th, td {
            padding: 12px;
            border: none; /* No borders */
            text-align: center;
        }
        th {
            background-color: #343a40;
            color: white;
        }
        select, button {
            padding: 5px;
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
    </style>
</head>
<body>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Select NGO</th>
                <th>Assign</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_unassigned->num_rows > 0) {
                while ($row = $result_unassigned->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['pickup_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['food_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity'] . " " . $row['unit']) . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='food_id' value='" . $row['fooddetails_id'] . "'>";
                    echo "<select name='ngo_id' required>";
                    echo "<option value=''>-- Select NGO --</option>";

                    $addr = $conn->real_escape_string($row['pickup_address']);
                    $ngo_sql = "SELECT * FROM ngo WHERE address = '$addr'";
                    $ngo_result = $conn->query($ngo_sql);
                    while ($ngo = $ngo_result->fetch_assoc()) {
                        echo "<option value='" . $ngo['ngo_id'] . "'>" . htmlspecialchars($ngo['ngo_name']) . " (" . htmlspecialchars($ngo['address']) . ")</option>";
                    }

                    echo "</select>";
                    echo "</td>";
                    echo "<td><button type='submit'>Assign</button></td>";
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
