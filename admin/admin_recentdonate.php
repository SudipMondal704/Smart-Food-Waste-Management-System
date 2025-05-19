<?php
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
$donateQuery = "SELECT COUNT(*) as donate_count FROM fooddetails";
$donateResult = $conn->query($donateQuery);
$donateCount = 0;
if ($donateResult && $donateResult->num_rows > 0) {
    $donateCount = $donateResult->fetch_assoc()['donate_count'];
}

// Count pending donations
// Assuming there's a status column in fooddetails table where 'pending' indicates pending status
// If your database structure is different, modify this query accordingly
$pendingQuery = "SELECT COUNT(*) as pending_count FROM fooddetails WHERE status = 'pending'";
$pendingResult = $conn->query($pendingQuery);
$pendingCount = 0;
if ($pendingResult && $pendingResult->num_rows > 0) {
    $pendingCount = $pendingResult->fetch_assoc()['pending_count'];
}

// Close connection
$conn->close();
?>

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
            <p>Members</p>
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