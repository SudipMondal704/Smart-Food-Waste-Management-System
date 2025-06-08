<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete Logic
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM ngo WHERE ngo_id = $delete_id");
    header("Location:admin.php?type=ngos");
    exit;
}

// Update Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_ngo'])) {
    $ngo_id = intval($_POST['ngo_id']);
    $ngo_name = $conn->real_escape_string($_POST['ngo_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $conn->query("UPDATE ngo SET ngo_name='$ngo_name', address='$address', email='$email', phone='$phone' WHERE ngo_id=$ngo_id");
}

$result = $conn->query("SELECT * FROM ngo");
?>

<!DOCTYPE html>
<html>
<head>
    <title>NGO List</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

       
</head>
<body>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Organization Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Registered</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['ngo_id'] ?></td>
                <td><?= htmlspecialchars($row['ngo_name']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <button class="btn btn-edit"
                        onclick="openModal('<?= $row['ngo_id'] ?>','<?= htmlspecialchars($row['ngo_name']) ?>','<?= htmlspecialchars($row['address']) ?>','<?= htmlspecialchars($row['email']) ?>','<?= htmlspecialchars($row['phone']) ?>')">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
                <td>
                    <a class="btn btn-delete" href="admin_ngo.php?delete_id=<?= $row['ngo_id'] ?>" onclick="return confirm('Are you sure you want to delete this NGO?');">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2>Edit NGO</h2>
        <form method="POST">
            <input type="hidden" name="ngo_id" id="edit_id">
            <label>Organization Name</label>
            <input type="text" name="ngo_name" id="edit_name" required>
            <label>Address</label>
            <input type="text" name="address" id="edit_address" required>
            <label>Email</label>
            <input type="email" name="email" id="edit_email" required>
            <label>Phone</label>
            <input type="tel" name="phone" id="edit_phone" required>
            <button type="submit" name="update_ngo">Update</button>
        </form><br>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
    function openModal(id, name, address, email, phone) {
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_address").value = address;
        document.getElementById("edit_email").value = email;
        document.getElementById("edit_phone").value = phone;
        document.getElementById("editModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("editModal").style.display = "none";
    }

    window.onclick = function(e) {
        const modal = document.getElementById("editModal");
        if (e.target === modal) {
            closeModal();
        }
    };
</script>

</body>
</html>
