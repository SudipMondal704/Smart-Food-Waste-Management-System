<?php
$conn = new mysqli("localhost", "root", "", "food_waste");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all NGOs with their address for filtering
$ngoResult = $conn->query("SELECT ngo_id, ngo_name, address FROM ngo ORDER BY ngo_name");
$ngosByAddress = [];
while ($ngo = $ngoResult->fetch_assoc()) {
    $addr = $ngo['address'] ?? '';  // assuming ngo_address column exists
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
    $assigned_ngo_id = $_POST['assigned_ngo_id'] ?: NULL; // NULL if none selected

    $stmt = $conn->prepare("UPDATE fooddetails SET 
        food_name=?, food_type=?, quantity=?, unit=?, assigned_ngo_id=?
        WHERE fooddetails_id=?");
    $stmt->bind_param("sssiii", $food_name, $food_type, $quantity, $unit, $assigned_ngo_id, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_id = $_POST['id'];
    $conn->query("DELETE FROM fooddetails WHERE fooddetails_id='$delete_id'");
}

// Fetch Food Details with assigned NGO name (if any)
$submissions = [];
$res = $conn->query("SELECT f.*, n.ngo_name FROM fooddetails f LEFT JOIN ngo n ON f.assigned_ngo_id = n.ngo_id ORDER BY f.submission_id, f.fooddetails_id");
while ($row = $res->fetch_assoc()) {
    $submissionId = $row['submission_id'];
    $submissions[$submissionId][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Details</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
             font-family: Arial; 
             background: #f4f4f4;
             padding: 0px 30px 30px 30px;
             }
        .submission-box {
             background: white;
              padding: 20px;
               margin-bottom: 30px;
                border-radius: 10px;
                 box-shadow: 0 0 8px rgba(0,0,0,0.2);
                 }
        table {
             width: 100%; 
             border-collapse: collapse;
              margin-top: 10px;
             }
        th, td { 
            padding: 10px;
             border: 1px solid #ddd;
              text-align: center; }
        th { background: #343a40; 
            color: white; 
        }
        img {
             width: 70px;
              height: 70px;
               object-fit: cover;
             }
        .edit-btn, .delete-btn { 
            padding: 5px 8px;
             border: none;
              border-radius: 4px; 
              color: white;
               cursor: pointer;
             }
        .edit-btn {
             background: #28a745; 
            }
        .edit-btn:hover { 
            background: #218838; 
        }
        .delete-btn {
             background: #dc3545;
             }
        .delete-btn:hover {
             background: #c82333;
             }
        .not-assigned {
             color: red;
             }
        .modal { 
            display: none;
             position: fixed;
              z-index: 999;
               left: 0;
                top: 0; 
                width: 100%;
                 height: 100%;
                  background-color: rgba(0,0,0,0.5);
                 }
        .modal-content {
             background-color: #fff;
             margin: 5% auto;
              padding: 20px;
               border-radius: 10px;
                width: 400px; 
                position: relative; 
            }
        .close { position: absolute; 
            right: 15px;
            top: 10px;
             font-size: 25px;
              font-weight: bold;
               color: #333;
               cursor: pointer; 
            }
        .modal-content form {
             display: flex;
              flex-direction: column;
               gap: 10px;
             }
        .modal-content input, .modal-content select {
             padding: 8px;
              border: 1px solid #ccc;
               border-radius: 6px; 
            }
        .modal-content button { 
           background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
             }
        .update-btn {
             background: #28a745;
             }
        .delete-btn-modal {
             background: #dc3545; 
            }
    </style>
</head>
<body>

<?php foreach ($submissions as $submissionId => $foods): ?>
<div class="submission-box">
    <h3> Donor: <?= htmlspecialchars($foods[0]['donor_name']) ?></h3>

    <table>
        <tr>
            <th>Food Name</th><th>Food Type</th><th>Qty</th><th>Unit</th>
            <th>Pickup Address</th><th>Phone</th><th>Image</th><th>Request Date</th><th>Assigned NGO</th><th>Edit</th><th>Delete</th>
        </tr>
        <?php foreach ($foods as $row):
            $img = "uploads/" . basename($row['image']);
            $imageTag = file_exists($img) ? "<img src='$img' alt='Food Image' style='width: 75px; height: 75px; object-fit: cover; border: 1px solid #ccc; border-radius:0px;'>" : "No Image";
            $ngo = $row['ngo_name'] ? htmlspecialchars($row['ngo_name']) : "<span class='not-assigned'>Not Assigned</span>";
        ?>
        <tr>
            <td><?= htmlspecialchars($row['food_name']) ?></td>
            <td><?= htmlspecialchars($row['food_type']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= htmlspecialchars($row['unit']) ?></td>
            <td><?= htmlspecialchars($row['pickup_address']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= $imageTag ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $ngo ?></td>
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

            <select name="assigned_ngo_id" id="modal_assigned_ngo_id">
                <option value="">-- Select Assigned NGO (Optional) --</option>
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

        const ngoSelect = document.getElementById('modal_assigned_ngo_id');
        ngoSelect.innerHTML = '<option value="">-- Select Assigned NGO (Optional) --</option>'; // reset options

        // Get NGOs matching the pickup address (case-insensitive)
        let pickupAddr = data.pickup_address.toLowerCase();

        // Find NGOs whose address matches pickup address (exact or you can customize match logic)
        let matchedNGOs = [];

        // ngosByAddress keys are original addresses, so we check for matching ignoring case
        for (const addr in ngosByAddress) {
            if (addr.toLowerCase() === pickupAddr) {
                matchedNGOs = ngosByAddress[addr];
                break;
            }
        }

        // If no exact match found, show "No NGO found" option or keep empty
        if (matchedNGOs.length === 0) {
            ngoSelect.innerHTML += '<option disabled>No NGOs available for this address</option>';
        } else {
            matchedNGOs.forEach(ngo => {
                const opt = document.createElement('option');
                opt.value = ngo.ngo_id;
                opt.textContent = ngo.ngo_name;
                ngoSelect.appendChild(opt);
            });
        }

        // Set currently assigned NGO if present
        ngoSelect.value = data.assigned_ngo_id || "";

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