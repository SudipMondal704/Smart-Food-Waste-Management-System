<?php

// Check if user is logged in as admin
require_once('adminSession.php');



// Database connection
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count total users (members)
$memberQuery = "SELECT COUNT(*) as member_count FROM users";
$memberResult = $conn->query($memberQuery);
$memberCount = 0;
if ($memberResult && $memberResult->num_rows > 0) {
    $memberCount = $memberResult->fetch_assoc()['member_count'];
}

// Count total donations
$donateQuery = "SELECT COUNT(*) as donate_count FROM fooddetails WHERE assigned_ngo_id IS NOT NULL";
$donateResult = $conn->query($donateQuery);
$donateCount = 0;
if ($donateResult && $donateResult->num_rows > 0) {
    $donateCount = $donateResult->fetch_assoc()['donate_count'];
}

// Count total NGOs
$ngoQuery = "SELECT COUNT(*) as ngo_count FROM ngo";
$ngoResult = $conn->query($ngoQuery);
$ngoCount = 0;
if ($ngoResult && $ngoResult->num_rows > 0) {
    $ngoCount = $ngoResult->fetch_assoc()['ngo_count'];
}

// Count pending donations
// Count pending donations (both NGO NOT FOUND and unassigned)
$pendingQuery = "SELECT COUNT(*) as pending_count FROM fooddetails WHERE assigned_ngo_id IS NULL OR assigned_ngo_id = 0";
$pendingResult = $conn->query($pendingQuery);
$pendingCount = 0;
if ($pendingResult && $pendingResult->num_rows > 0) {
    $pendingCount = $pendingResult->fetch_assoc()['pending_count'];
}


// Close connection
$conn->close();
?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.box-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.box-info li {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-width: 200px;
    flex: 1;
}

.box-info li i {
    font-size: 36px;
    margin-right: 15px;
    color: #3498db;
}

.box-info li .text h3 {
    font-size: 28px;
    margin: 0;
    color: #2c3e50;
    font-weight: bold;
}

.box-info li .text p {
    margin: 5px 0 0 0;
    color: #7f8c8d;
    font-size: 14px;
}

/* Responsive design */
@media (max-width: 768px) {
    .box-info {
        flex-direction: column;
    }
    
    .box-info li {
        min-width: auto;
    }
}
</style>

<ul class="box-info">
    <li>
        <i class="fi fi-ss-hands-heart"></i>
        <span class="text">
            <h3><?php echo $donateCount; ?></h3>
            <p>Total Donates</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3><?php echo $memberCount; ?></h3>
            <p>Donors</p>
        </span>
    </li>
    <li>
        <i class="fi fi-ss-building"></i>
        <span class="text">
            <h3><?php echo $ngoCount; ?></h3>
            <p>Total NGOs</p>
        </span>
    </li>
    <li>
        <i class="fi fi-ss-pending"></i>
        <span class="text">
            <h3><?php echo $pendingCount; ?></h3>
            <p>Pending Donates</p>
        </span>
    </li>
</ul>