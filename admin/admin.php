<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Login.php?message=Please login as admin.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
	<!-- Custom CSS -->
	<link rel="stylesheet" href="style.css">

	<title>Admin Panel</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class="fi fi-ss-admin-alt"></i>
			<span class="text">Admin</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class="fi fi-ss-users"></i>
					<span class="text">Customers</span>
					<i class='bx bx-chevron-down dropdown-icon'></i>	
				</a>
				<ul class="sub-menu">
					<li><a href="../Registration.php">Create <i class="fi fi-ss-plus"></i></a></li>
					<li><a href="?type=users">List <i class="fi fi-ss-list"></i></a></li>
				</ul>
			</li>
			<li>
				<a href="#">
					<i class="bx bxs-home"></i>
					<span class="text">NGOs</span>
					<i class='bx bx-chevron-down dropdown-icon'></i>
				</a>
				<ul class="sub-menu">
					<li><a href="../NGOReg.php">Create <i class="fi fi-ss-plus"></i></a></li>
					<li><a href="?type=ngo">List <i class="fi fi-ss-list"></i></a></li>
				</ul>
			</li>
			<li>
				<a href="#">
					<i class="fi fi-ss-hamburger-soda"></i>
					<span class="text">Food Details</span>
				</a>
			</li>
			<li>
				<a href="?type=feedback">
					<i class='bx bxs-file'></i>
					<span class="text">Feedbacks</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="AdminLogout.php" class="logout">
					<i class="fi fi-ss-sign-out-alt"></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- END SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification"><i class='bx bxs-bell'></i></a>
			<a href="#" class="profile"><img src="user.jpg" alt="Admin Profile"></a>
		</nav>

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li><a href="#">Dashboard</a></li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li><a class="active" href="#">Home</a></li>
					</ul>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class="fi fi-ss-hands-heart"></i>
					<span class="text"><h3>1020</h3><p>Total Donates</p></span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text"><h3>2834</h3><p>Members</p></span>
				</li>
				<li>
					<i class="fi fi-ss-pending"></i>
					<span class="text"><h3>143</h3><p>Pending Donates</p></span>
				</li>
			</ul>

			<div id="dynamic-content">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "food_waste";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    switch ($type) {
        case 'users':
            $sql = "SELECT user_id, username, address, email, phone, gender, created_at FROM users";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>User List</h3></div><table><thead><tr><th>User ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone</th><th>Gender</th><th>Registered At</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['user_id']}</td><td>{$row['username']}</td><td>{$row['address']}</td><td>{$row['email']}</td><td>{$row['phone']}</td><td>{$row['gender']}</td><td>{$row['created_at']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No users found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        case 'ngo':
            $sql = "SELECT ngo_id, ngo_name, address, email, phone, created_at FROM ngo";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>NGO List</h3></div><table><thead><tr><th>NGO ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone</th><th>Registered At</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['ngo_id']}</td><td>{$row['ngo_name']}</td><td>{$row['address']}</td><td>{$row['email']}</td><td>{$row['phone']}</td><td>{$row['created_at']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No NGOs found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        case 'feedback':
            $sql = "SELECT feedback_id, user_id, full_name, email, phone_number, feedback_text, feedback_date FROM feedback";
            $result = $conn->query($sql);
            echo '<div class="table-data"><div class="order"><div class="head"><h3>Feedback List</h3></div><table><thead><tr><th>ID</th><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Feedback</th><th>Date</th></tr></thead><tbody>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['feedback_id']}</td><td>{$row['user_id']}</td><td>{$row['full_name']}</td><td>{$row['email']}</td><td>{$row['phone_number']}</td><td>{$row['feedback_text']}</td><td>{$row['feedback_date']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No feedback found</td></tr>";
            }
            echo '</tbody></table></div></div>';
            break;

        default:
            echo "<p>Invalid section selected.</p>";
    }
}
?>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Donates</h3>
						<i class='bx bx-search'></i>
						<i class='bx bx-filter'></i>
					</div>
					<table>
						<thead>
							<tr><th>Name</th><th>Location</th><th>Contact No.</th><th>Date</th><th>Status</th></tr>
						</thead>
						<tbody>
							<tr><td><img src="img/people.png"><p>AA</p></td><td>Asansol</td><td>+91 7853692142</td><td>25-03-2025</td><td><span class="status completed">Completed</span></td></tr>
							<tr><td><img src="img/people.png"><p>BB</p></td><td>Durgapur</td><td>+91 6853682142</td><td>26-03-2025</td><td><span class="status pending">Pending</span></td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</main>
	</section>

	<script src="script.js"></script>
</body>
</html>
