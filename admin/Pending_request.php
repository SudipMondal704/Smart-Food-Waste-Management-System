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

$message = "";

// Handle NGO assignment to all unassigned foods of the donor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $donor_id = intval($_POST['user_id']);
    $pickup_address = $conn->real_escape_string($_POST['pickup_address']);
    
    if (isset($_POST['ngo_id']) && !empty($_POST['ngo_id'])) {
        // Assign NGO
        $ngo_id = intval($_POST['ngo_id']);
        $stmt = $conn->prepare("UPDATE fooddetails SET assigned_ngo_id = ? WHERE user_id = ? AND pickup_address = ? AND (assigned_ngo_id IS NULL OR assigned_ngo_id = 'NGO NOT FOUND')");
        $stmt->bind_param("iis", $ngo_id, $donor_id, $pickup_address);
        
        if ($stmt->execute()) {
            $message = "NGO assigned to all pending foods of the donor at this address.";
        } else {
            $message = "Failed to assign NGO: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['ngo_not_found'])) {
        // Mark as NGO NOT FOUND
        $stmt = $conn->prepare("UPDATE fooddetails SET assigned_ngo_id = 'NGO NOT FOUND' WHERE user_id = ? AND pickup_address = ? AND assigned_ngo_id IS NULL");
        $stmt->bind_param("is", $donor_id, $pickup_address);
        
        if ($stmt->execute()) {
            $message = "Marked as 'NGO NOT FOUND' for this pickup address.";
        } else {
            $message = "Failed to update status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Get all unassigned donor details (one row per donor per address) including NGO NOT FOUND cases
$sql_unassigned_donors = "
    SELECT f.user_id, f.donor_name, f.pickup_address, u.username, u.address as user_address 
    FROM fooddetails f 
    JOIN users u ON f.user_id = u.user_id 
    WHERE f.assigned_ngo_id IS NULL OR f.assigned_ngo_id = 'NGO NOT FOUND'
    GROUP BY f.user_id, f.pickup_address";
$result_unassigned = $conn->query($sql_unassigned_donors);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Food Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-assign {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-assign:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .btn-not-found {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        select {
            width: 100%;
            padding: 5px;
        }
    </style>
</head>
<body>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Username (Phone)</th>
                <th>User Address</th>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Select NGO</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_unassigned->num_rows > 0) {
                while ($row = $result_unassigned->fetch_assoc()) {
                    $donor_id = intval($row['user_id']);
                    $donor_name = htmlspecialchars($row['donor_name']);
                    $pickup_address = htmlspecialchars($row['pickup_address']);
                    $username = htmlspecialchars($row['username']);
                    $user_address = htmlspecialchars($row['user_address']);

                    echo "<tr>";
                    echo "<form method='POST' id='form_$donor_id'>";
                    echo "<td>$username</td>";
                    echo "<td>$user_address</td>";
                    echo "<td>$donor_name</td>";
                    echo "<td>$pickup_address</td>";
                    echo "<td>";
                    echo "<select name='ngo_id' id='ngo_select_$donor_id' onchange='toggleAssignButton($donor_id)'>";
                    echo "<option value=''>-- Select NGO --</option>";

                    $hasNGO = false;
                    // Fixed NGO query - directly match NGO address with pickup address
                    $stmt_ngo = $conn->prepare("SELECT * FROM ngo WHERE LOWER(address) = LOWER(?)");
                    $stmt_ngo->bind_param("s", $row['pickup_address']);
                    $stmt_ngo->execute();
                    $ngo_result = $stmt_ngo->get_result();

                    if ($ngo_result && $ngo_result->num_rows > 0) {
                        $hasNGO = true;
                        while ($ngo = $ngo_result->fetch_assoc()) {
                            $ngo_id = intval($ngo['ngo_id']);
                            $ngo_name = htmlspecialchars($ngo['ngo_name']);
                            $ngo_address = htmlspecialchars($ngo['address']);
                            echo "<option value='$ngo_id'>$ngo_name ($ngo_address)</option>";
                        }
                    }
                    $stmt_ngo->close();

                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<div class='action-buttons'>";
                    echo "<input type='hidden' name='user_id' value='$donor_id'>";
                    echo "<input type='hidden' name='pickup_address' value='" . htmlspecialchars($row['pickup_address']) . "'>";
                    
                    if ($hasNGO) {
                        echo "<button type='submit' id='assign_btn_$donor_id' class='btn-assign' disabled>Assign</button>";
                    } else {
                        echo "<button type='submit' name='ngo_not_found' class='btn-not-found'>NGO NOT FOUND</button>";
                    }
                    
                    echo "</div>";
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

    <script>
        function toggleAssignButton(donorId) {
            const select = document.getElementById('ngo_select_' + donorId);
            const assignBtn = document.getElementById('assign_btn_' + donorId);
            
            if (assignBtn) {
                if (select.value !== '') {
                    assignBtn.disabled = false;
                } else {
                    assignBtn.disabled = true;
                }
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>