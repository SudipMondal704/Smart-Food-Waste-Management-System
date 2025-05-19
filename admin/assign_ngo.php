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

$message = "";

// Handle NGO assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['food_id']) && isset($_POST['ngo_id'])) {
    $food_id = $_POST['food_id'];
    $ngo_id = $_POST['ngo_id'];

    $updateQuery = "UPDATE fooddetails SET assigned_ngo_id = $ngo_id WHERE fooddetails_id = $food_id";
    if (mysqli_query($conn, $updateQuery)) {
        $message = " NGO assigned successfully!";
    } else {
        $message = "Failed to assign NGO.";
    }
}

// Fetch unassigned donations
$unassignedQuery = "SELECT * FROM fooddetails WHERE assigned_ngo_id IS NULL";
$unassignedResult = mysqli_query($conn, $unassignedQuery);

// Fetch assigned donations with NGO info
$assignedQuery = "SELECT f.*, n.ngo_name, n.address AS ngo_address 
                  FROM fooddetails f 
                  JOIN ngo n ON f.assigned_ngo_id = n.ngo_id 
                  WHERE f.assigned_ngo_id IS NOT NULL";
$assignedResult = mysqli_query($conn, $assignedQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign NGO</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
    padding:0px 30px 30px 30px;

        margin: 0;
    }
    h3 {
        margin-bottom: 20px;
        color: #444;
    }
    .message {
        margin-bottom: 20px;
        padding: 10px;
        background: #e0ffe0;
        border: 1px solid #b2ffb2;
        color: #006600;
        width: fit-content;
        border-radius: 4px;
    }
    .table-container {
        overflow-x: auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px #ccc;
        margin-bottom: 40px;
        width: 100%;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: auto;
    }
    th, td {
        padding: 12px 15px;
        border: 1px solid #ccc;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #343a40;
        color: #fff;
    }
    select {
        padding: 6px;
        width: 100%;
    }
    button {
        padding: 7px 12px;
        background-color: #28a745;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
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

<!-- Unassigned Donations -->
<div class="table-container">
    <h3>Pending Requests</h3>
    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>NGO</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($unassignedResult)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_address']); ?></td>
                <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity'] . ' ' . $row['unit']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="food_id" value="<?php echo $row['fooddetails_id']; ?>">
                        <select name="ngo_id" required>
                            <option value="">-- Select NGO --</option>
                            <?php
                            $addr = $row['pickup_address'];
                            $ngoQuery = "SELECT * FROM ngo WHERE address = '$addr'";
                            $ngoResult = mysqli_query($conn, $ngoQuery);
                            while ($ngo = mysqli_fetch_assoc($ngoResult)) {
                                echo "<option value='{$ngo['ngo_id']}'>" . htmlspecialchars($ngo['ngo_name']) . " ({$ngo['address']})</option>";
                            }
                            ?>
                        </select>
                </td>
                <td>
                        <button type="submit">Assign</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Assigned Donations -->
<div class="table-container">
    <h3>Assigned Requests</h3>
    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Pickup Address</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Assigned NGO</th>
                <th>NGO Address</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($assignedResult)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_address']); ?></td>
                <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity'] . ' ' . $row['unit']); ?></td>
                <td><?php echo htmlspecialchars($row['ngo_name']); ?></td>
                <td><?php echo htmlspecialchars($row['ngo_address']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
