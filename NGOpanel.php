<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'ngo') {
    echo "<script>alert('Please login as NGO first!'); window.location.href='newlogin.php';</script>";
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "food_waste");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Accept/Deny actions
if (isset($_POST['action']) && isset($_POST['food_id'])) {
    $food_id = (int)$_POST['food_id'];
    $action = $_POST['action'];
    
    if ($action === 'accept') {
        $update_query = "UPDATE fooddetails SET status = 'accepted' WHERE fooddetails_id = ? AND assigned_ngo_id = ?";
        $message = "Food donation accepted successfully!";
    } elseif ($action === 'deny') {
        // Modified: Only set status to 'denied' but keep the NGO assigned for tracking
        // The admin should handle reassignment and status reset
        $update_query = "UPDATE fooddetails SET status = 'denied' WHERE fooddetails_id = ? AND assigned_ngo_id = ?";
        $message = "Food donation denied. Admin will be notified for reassignment.";
    }
    
    if (isset($update_query)) {
        $stmt = $conn->prepare($update_query);
        if ($stmt) {
            $stmt->bind_param("ii", $food_id, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('$message'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            } else {
                echo "<script>alert('Error updating status: " . $conn->error . "');</script>";
            }
            $stmt->close();
        }
    }
}

// Handle Delete action using PHP
if (isset($_POST['delete_food_id'])) {
    $food_id = (int)$_POST['delete_food_id'];
    
    $delete_query = "DELETE FROM fooddetails WHERE fooddetails_id = ? AND assigned_ngo_id = ?";
    $stmt = $conn->prepare($delete_query);
    if ($stmt) {
        $stmt->bind_param("ii", $food_id, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Food request deleted successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        } else {
            echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error occurred');</script>";
    }
}

// Get NGO details using prepared statement
$ngo_query = "SELECT ngo_name, email, phone, address FROM ngo WHERE ngo_id = ?";
$stmt = $conn->prepare($ngo_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ngo_result = $stmt->get_result();

$ngo_name = "";
$ngo_email = "";
$ngo_phone = "";
$ngo_address = "";

if ($ngo_result && $ngo_result->num_rows > 0) {
    $ngo_data = $ngo_result->fetch_assoc();
    $ngo_name = $ngo_data['ngo_name'];
    $ngo_email = $ngo_data['email'] ?? '';
    $ngo_phone = $ngo_data['phone'] ?? '';
    $ngo_address = $ngo_data['address'] ?? '';
} else {
    // If NGO not found, logout
    session_destroy();
    echo "<script>alert('NGO account not found!'); window.location.href='newlogin.php';</script>";
    exit();
}
$stmt->close();

// Get assigned food donations using prepared statement
// Modified: Show donations that are assigned to this NGO and not denied by this NGO
$donations_query = "SELECT fd.*, u.username as donor_username 
                   FROM fooddetails fd 
                   LEFT JOIN users u ON fd.user_id = u.user_id 
                   WHERE fd.assigned_ngo_id = ? 
                   ORDER BY fd.fooddetails_id DESC";
$stmt2 = $conn->prepare($donations_query);
if (!$stmt2) {
    die("Prepare failed: " . $conn->error);
}
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$donations_result = $stmt2->get_result();

// Get donation statistics
$stats_query = "SELECT 
    COUNT(*) as total_donations,
    SUM(CASE WHEN status = 'pending' OR status IS NULL OR status = 'assigned' THEN 1 ELSE 0 END) as pending_donations,
    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted_donations,
    SUM(CASE WHEN status = 'denied' THEN 1 ELSE 0 END) as denied_donations
    FROM fooddetails WHERE assigned_ngo_id = ?";
$stmt3 = $conn->prepare($stats_query);
if (!$stmt3) {
    die("Prepare failed: " . $conn->error);
}
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$stats_result = $stmt3->get_result();
$stats = $stats_result->fetch_assoc();
$stmt3->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Dashboard - Food Waste Management</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-top: 60px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(52, 73, 94, 0.95);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .user-info {
            align-items: center;
            gap: 20px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .stat-card i {
            font-size: 1.2rem;
            margin-right: 8px;
        }

        .stat-card.pending {
            background: linear-gradient(135deg, #ff9800, #f57c00);
        }

        .stat-card.accepted {
            background: linear-gradient(135deg, #4caf50, #388e3c);
        }

        .stat-card.denied {
            background: linear-gradient(135deg, #f44336, #d32f2f);
        }

        .ngo-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #007bff;
        }

        .ngo-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .ngo-info h3 i {
            margin-right: 10px;
            color: #007bff;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }

        .ngo-info p {
            color: #6c757d;
            margin-bottom: 8px;
            padding: 8px;
            background: white;
            border-radius: 5px;
        }

        .ngo-info p i {
            margin-right: 8px;
            color: #007bff;
            width: 20px;
        }

        .donations-section {
            margin-top: 30px;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #3498db;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-pending, .status-assigned {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-denied {
            background-color: #f8d7da;
            color: #842029;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn i {
            font-size: 0.9rem;
        }

        .btn-accept {
            background-color: #28a745;
            color: white;
        }

        .btn-accept:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-deny {
            background-color: #dc3545;
            color: white;
        }

        .btn-deny:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 8px 10px;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .delete-column {
            text-align: center;
            width: 80px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin-top: 80px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
            
            th, td {
                padding: 8px;
                font-size: 0.85rem;
            }

            .top-bar {
                flex-direction: column;
                gap: 10px;
                padding: 10px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        .loading {
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="return" style="text-align: left; background-color: white; color: #252525; border-radius: 5px; padding: 10px 15px;">
            <a href="home/homeSession.php" style="text-decoration: none;"><i class="fi fi-ss-angle-double-small-left"></i> Back to home</a>
        </div>
        <div class="user-info">
            <p style="font-size: 18px;">Welcome, <strong><?= htmlspecialchars($ngo_name) ?></strong></p>
        </div>
    </div>
    
    <div class="container">
        <div class="header">
            <h1>NGO Dashboard</h1>
            <p>Food Waste Management System</p>
        </div>

        <div class="ngo-info">
            <h3><i class="fas fa-clipboard-list"></i> NGO Information</h3>
            <div class="info-grid">
                <p><i class="fas fa-building"></i> <strong>Name:</strong> <?= htmlspecialchars($ngo_name) ?></p>
                <?php if (!empty($ngo_email)): ?>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?= htmlspecialchars($ngo_email) ?></p>
                <?php endif; ?>
                <?php if (!empty($ngo_phone)): ?>
                    <p><i class="fas fa-phone"></i> <strong>Phone:</strong> <?= htmlspecialchars($ngo_phone) ?></p>
                <?php endif; ?>
                <?php if (!empty($ngo_address)): ?>
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> <?= htmlspecialchars($ngo_address) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3><?= $stats['total_donations'] ?? 0 ?></h3>
                <p><i class="fas fa-chart-bar"></i> Total Donations</p>
            </div>
            <div class="stat-card pending">
                <h3><?= $stats['pending_donations'] ?? 0 ?></h3>
                <p><i class="fas fa-clock"></i> Pending Review</p>
            </div>
            <div class="stat-card accepted">
                <h3><?= $stats['accepted_donations'] ?? 0 ?></h3>
                <p><i class="fas fa-check-circle"></i> Accepted</p>
            </div>
            <div class="stat-card denied">
                <h3><?= $stats['denied_donations'] ?? 0 ?></h3>
                <p><i class="fas fa-times-circle"></i> Denied</p>
            </div>
        </div>

        <div class="donations-section">
            <h2 class="section-title">
                <i class="fas fa-utensils"></i> Assigned Food Donations
            </h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Donor Name</th>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Pickup Address</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($donations_result && $donations_result->num_rows > 0) {
                            $serial_no = 1;
                            while ($donation = $donations_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $serial_no . "</td>";
                                echo "<td>" . htmlspecialchars($donation['donor_name'] ?? $donation['donor_username'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($donation['food_name'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($donation['quantity'] ?? 'N/A') . " " . htmlspecialchars($donation['unit'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($donation['pickup_address'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($donation['phone'] ?? $donation['alt_phone'] ?? 'N/A') . "</td>";
                                
                                // Status badge
                                $status = $donation['status'] ?? 'assigned';
                                $status_class = 'status-' . strtolower($status);
                                echo "<td><span class='status-badge $status_class'>" . ucfirst($status) . "</span></td>";
                                
                                // Date
                                $date = $donation['created_at'] ?? date('Y-m-d');
                                if ($date && $date != 'N/A') {
                                    $formatted_date = date('M j, Y', strtotime($date));
                                    echo "<td>" . $formatted_date . "</td>";
                                } else {
                                    echo "<td>N/A</td>";
                                }
                                
                                // Action buttons - Modified logic
                                echo "<td class='action-buttons'>";
                                // Allow actions only if status is assigned, pending, or null (not denied, accepted, or completed)
                                if (in_array($status, ['assigned', 'pending', null, ''])) {
                                    echo "<form method='POST' style='display: inline;' onsubmit='return confirmAction(\"accept\")'>";
                                    echo "<input type='hidden' name='food_id' value='" . $donation['fooddetails_id'] . "'>";
                                    echo "<input type='hidden' name='action' value='accept'>";
                                    echo "<button type='submit' class='btn btn-accept'><i class='fas fa-check'></i> Accept</button>";
                                    echo "</form>";
                                    
                                    echo "<form method='POST' style='display: inline;' onsubmit='return confirmAction(\"deny\")'>";
                                    echo "<input type='hidden' name='food_id' value='" . $donation['fooddetails_id'] . "'>";
                                    echo "<input type='hidden' name='action' value='deny'>";
                                    echo "<button type='submit' class='btn btn-deny'><i class='fas fa-times'></i> Deny</button>";
                                    echo "</form>";
                                } else {
                                    echo "<span style='color: #6c757d; font-size: 0.8rem;'><i class='fas fa-check-double'></i> Action Completed</span>";
                                }
                                echo "</td>";
                                
                                // Delete button in separate column
                                echo "<td class='delete-column'>";
                                echo "<form method='POST' style='display: inline;' onsubmit='return confirmDelete()'>";
                                echo "<input type='hidden' name='delete_food_id' value='" . $donation['fooddetails_id'] . "'>";
                                echo "<button type='submit' class='btn btn-delete' title='Delete Record'><i class='fas fa-trash'></i></button>";
                                echo "</form>";
                                echo "</td>";
                                
                                echo "</tr>";
                                $serial_no++;
                            }
                        } else {
                            echo "<tr><td colspan='10' class='no-data'>
                                    <i class='fas fa-inbox'></i><br>
                                    <strong>No donations assigned yet.</strong><br>
                                    <small>Donations will appear here once assigned by the admin.</small>
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmAction(action) {
            if (action === 'accept') {
                return confirm('Are you sure you want to ACCEPT this food donation? This will mark it as accepted for pickup.');
            } else if (action === 'deny') {
                return confirm('Are you sure you want to DENY this food donation? The admin will be notified to reassign it to another NGO.');
            }
            return false;
        }

        function confirmDelete() {
            return confirm('Are you sure you want to delete this food request? This action cannot be undone.');
        }
        
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            console.log('NGO Dashboard loaded successfully');
            console.log('User ID: <?= $user_id ?>');
            console.log('NGO Name: <?= htmlspecialchars($ngo_name) ?>');
            
            // Add click effect to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });

            // Add hover effects to action buttons
            const actionButtons = document.querySelectorAll('.btn');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
            });
        });

        // Prevent back button after logout
        if (performance.navigation.type === 2) {
            location.reload();
        }
    </script>
</body>
</html>

<?php
// Close remaining prepared statements and database connection
if (isset($stmt2)) $stmt2->close();
$conn->close();
?>