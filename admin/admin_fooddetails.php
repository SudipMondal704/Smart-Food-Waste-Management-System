<?php

// Check if user is logged in as admin
require_once('adminSession.php');


$conn = new mysqli("localhost", "root", "", "food_waste");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all NGOs with their address for filtering - based on pickup addresses from fooddetails
$ngoResult = $conn->query("
    SELECT DISTINCT n.ngo_id, n.ngo_name, n.address, f.pickup_address
    FROM ngo n 
    LEFT JOIN fooddetails f ON LOWER(n.address) = LOWER(f.pickup_address)
    ORDER BY n.ngo_name
");
$ngosByAddress = [];
while ($ngo = $ngoResult->fetch_assoc()) {
    $addr = $ngo['pickup_address'] ?? $ngo['address'];  // use pickup_address if available
    if (!isset($ngosByAddress[$addr])) {
        $ngosByAddress[$addr] = [];
    }
    $ngosByAddress[$addr][] = $ngo;
}

// Handle Edit (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $food_name = $_POST['food_name'];
    $food_type = $_POST['food_type'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $pickup_address = $_POST['pickup_address'];
    $assigned_ngo_id = $_POST['assigned_ngo_id'] ?: NULL; // NULL if none selected
    
    // Additional fields for submissions table
    $donor_name = $_POST['donor_name'];
    $phone = $_POST['phone'];
    $alt_phone = $_POST['alt_phone'];

    // When reassigning NGO, reset status to pending if it was denied
    $status_update = "";
    if ($assigned_ngo_id && $assigned_ngo_id != 'NGO NOT FOUND') {
        // Check current status
        $current_status_query = "SELECT status FROM fooddetails WHERE fooddetails_id = ?";
        $stmt_check = $conn->prepare($current_status_query);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $current_result = $stmt_check->get_result();
        $current_data = $current_result->fetch_assoc();
        $stmt_check->close();
        
        // If status was denied and we're assigning new NGO, reset to pending
        if ($current_data && $current_data['status'] == 'denied') {
            $status_update = ", status = 'pending'";
        }
    }

    // Update fooddetails table
    $update_query = "UPDATE fooddetails SET 
        food_name=?, food_type=?, quantity=?, unit=?, pickup_address=?, assigned_ngo_id=?, donor_name=?, phone=?" . $status_update . "
        WHERE fooddetails_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssississi", $food_name, $food_type, $quantity, $unit, $pickup_address, $assigned_ngo_id, $donor_name, $phone, $id);
    $stmt->execute();
    $stmt->close();
    
    // Get submission_id from fooddetails to update submissions table
    $submissionResult = $conn->query("SELECT submission_id FROM fooddetails WHERE fooddetails_id='$id'");
    if ($submissionRow = $submissionResult->fetch_assoc()) {
        $submission_id = $submissionRow['submission_id'];
        
        // Update submissions table
        $stmt2 = $conn->prepare("UPDATE submissions SET 
            donor_name=?, pickup_address=?, phone=?, alt_phone=?
            WHERE submission_id=?");
        $stmt2->bind_param("ssssi", $donor_name, $pickup_address, $phone, $alt_phone, $submission_id);
        $stmt2->execute();
        $stmt2->close();
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_id = $_POST['id'];
    $conn->query("DELETE FROM fooddetails WHERE fooddetails_id='$delete_id'");
}

// Fetch Food Details with assigned NGO name (if any) and group by username and address
$submissions = [];
$res = $conn->query("
    SELECT f.*, 
           CASE 
               WHEN f.assigned_ngo_id = 'NGO NOT FOUND' THEN 'NGO NOT FOUND'
               ELSE n.ngo_name 
           END as ngo_name, 
           u.username, u.address as user_address,
           s.alt_phone
    FROM fooddetails f 
    LEFT JOIN ngo n ON f.assigned_ngo_id = n.ngo_id 
    JOIN users u ON f.user_id = u.user_id
    LEFT JOIN submissions s ON f.submission_id = s.submission_id
    ORDER BY u.username, f.pickup_address, f.submission_id, f.fooddetails_id
");
while ($row = $res->fetch_assoc()) {
    $groupKey = $row['username'] . '|' . $row['user_address'] . '|' . $row['pickup_address'];
    $submissions[$groupKey][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Details</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
.ngo-not-found {
    color: white !important;
    font-weight: bold !important;
    background-color: #dc3545 !important;
    padding: 4px 8px !important;
    border-radius: 4px !important;
    display: inline-block !important;
}

.ngo-assigned {
    color:black;
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

.not-assigned {
    color:rgb(255, 0, 0); /* Bootstrap's gray */
    background-color:rgb(255, 254, 253); /* Bootstrap's yellow */
    font-weight:bolder;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

/* Status styling */
.status-pending {
    color: white;
    background-color: #ffc107;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    font-weight: bold;
}

.status-accepted {
    color: white;
    background-color: #28a745;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    font-weight: bold;
}

.status-denied {
    color: white;
    background-color: #dc3545;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    font-weight: bold;
}

.status-completed {
    color: white;
    background-color: #007bff;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    font-weight: bold;
}
</style>
</head>
<body>

<?php foreach ($submissions as $groupKey => $foods): 
    $keyParts = explode('|', $groupKey);
    $username = $keyParts[0];
    $userAddress = $keyParts[1];
    $pickupAddress = $keyParts[2];
?>
<div class="submission-box">
    <div class="group-header">
        <div class="donor-info">
            <div class="donor-info-horizontal">
                User Name: <?= htmlspecialchars($username) ?> | Address: <?= htmlspecialchars($userAddress) ?>
            </div>
        </div>
        <div>
            <span class="food-count"><?= count($foods) ?> items</span>
        </div>
    </div>

    <table>
        <tr>
            <th>Food Name</th><th>Food Type</th><th>Qty</th><th>Unit</th>
            <th>Donor Name</th><th>Phone</th><th>Pickup Address</th><th>Image</th><th>Request Date</th><th>Assigned NGO</th><th>Status</th><th>Edit</th><th>Delete</th>
        </tr>
        <?php foreach ($foods as $row):
            $img = "uploads/" . basename($row['image']);
            $imageTag = file_exists($img) ? "<img src='$img' alt='Food Image' style='width: 75px; height: 75px; object-fit: cover; border: 1px solid #ccc; border-radius:4px;'>" : "No Image";
            
            // Handle different NGO status display
            if ($row['assigned_ngo_id'] === 'NGO NOT FOUND') {
                $ngo = "<span class='ngo-not-found'>NGO NOT FOUND</span>";
            } elseif ($row['ngo_name']) {
                $ngo = "<span class='ngo-assigned'>" . htmlspecialchars($row['ngo_name']) . "</span>";
            } else {
                $ngo = "<span class='not-assigned'>Not Assigned</span>";
            }
            
            // Handle status display - show pending by default if no status set
            $status = $row['status'] ?? 'pending';
            $status_display = "<span class='status-" . strtolower($status) . "'>" . ucfirst($status) . "</span>";
        ?>
        <tr>
            <td><?= htmlspecialchars($row['food_name']) ?></td>
            <td><?= htmlspecialchars($row['food_type']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= htmlspecialchars($row['unit']) ?></td>
            <td><?= htmlspecialchars($row['donor_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['pickup_address']) ?></td>
            <td><?= $imageTag ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $ngo ?></td>
            <td><?= $status_display ?></td>
            <td><button class="edit-btn" onclick='openModal(<?= json_encode($row) ?>)'><i class="fas fa-edit"></i></button></td>
            <td>
                <form method="post" onsubmit="return confirm('Delete this food entry?')">
                    <input type="hidden" name="id" value="<?= $row['fooddetails_id'] ?>">
                    <button type="submit" name="delete" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endforeach; ?>

<!-- Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
         <h2>Edit Donation Status</h2>
        <form method="post" id="modalForm">
            <input type="hidden" name="id" id="modal_id">
            <input type="text" name="food_name" id="modal_food_name" placeholder="Food Name" required>
            <input type="text" name="food_type" id="modal_food_type" placeholder="Food Type" required>
            <input type="number" name="quantity" id="modal_quantity" placeholder="Quantity" required>
            <input type="text" name="unit" id="modal_unit" placeholder="Unit" required>
            <input type="text" name="donor_name" id="modal_donor_name" placeholder="Donor Name" required>
            <input type="text" name="phone" id="modal_phone" placeholder="Phone" required>
            <input type="text" name="alt_phone" id="modal_alt_phone" placeholder="Alternative Phone">
            <input type="text" name="pickup_address" id="modal_pickup_address" placeholder="Pickup Address" required>

            <select name="assigned_ngo_id" id="modal_assigned_ngo_id">
                <option value="">-- Select Assigned NGO (Optional) --</option>
                <option value="NGO NOT FOUND">NGO NOT FOUND</option>
                <!-- Options will be populated dynamically based on pickup address -->
            </select>
                <button type="submit" name="update" class="update-btn">Update</button>
                </form><br>
        <button onclick="closeModal()">Cancel</button>
            </div>
    </div>
</div>

<script>
    // Pass PHP NGOs by address to JS
    const ngosByAddress = <?= json_encode($ngosByAddress) ?>;

    function openModal(data) {
        document.getElementById('modal_id').value = data.fooddetails_id;
        document.getElementById('modal_food_name').value = data.food_name;
        document.getElementById('modal_food_type').value = data.food_type;
        document.getElementById('modal_quantity').value = data.quantity;
        document.getElementById('modal_unit').value = data.unit;
        document.getElementById('modal_donor_name').value = data.donor_name;
        document.getElementById('modal_phone').value = data.phone;
        document.getElementById('modal_alt_phone').value = data.alt_phone || '';
        document.getElementById('modal_pickup_address').value = data.pickup_address;

        const ngoSelect = document.getElementById('modal_assigned_ngo_id');
        ngoSelect.innerHTML = '<option value="">-- Select Assigned NGO (Optional) --</option><option value="NGO NOT FOUND">NGO NOT FOUND</option>'; // reset options

        // Get NGOs matching the pickup address (case-insensitive)
        let pickupAddr = data.pickup_address.toLowerCase();

        // Find NGOs whose address matches pickup address (exact or you can customize match logic)
        let matchedNGOs = [];

        // ngosByAddress keys are original addresses, so we check for matching ignoring case
        for (const addr in ngosByAddress) {
            if (addr && addr.toLowerCase() === pickupAddr) {
                matchedNGOs = ngosByAddress[addr];
                break;
            }
        }

        // Add matched NGOs to dropdown
        if (matchedNGOs.length > 0) {
            matchedNGOs.forEach(ngo => {
                const opt = document.createElement('option');
                opt.value = ngo.ngo_id;
                opt.textContent = ngo.ngo_name + ' (' + ngo.address + ')';
                ngoSelect.appendChild(opt);
            });
        }

        // Set currently assigned NGO if present
        if (data.assigned_ngo_id === 'NGO NOT FOUND') {
            ngoSelect.value = "NGO NOT FOUND";
        } else {
            ngoSelect.value = data.assigned_ngo_id || "";
        }

        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>