<?php
session_start();

// Check if user is logged in and has donor role
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Donor') {
    echo "<script>alert('Login as Donor!'); window.location.href='newlogin.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection with error handling
try {
    $conn = new mysqli("localhost", "root", "", "food_waste");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Fetch donor name using prepared statement
$donor_name = "";
try {
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    if ($stmt === false) {
        throw new Exception("Prepare failed for donor query: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $donor_row = $result->fetch_assoc();
        $donor_name = htmlspecialchars($donor_row['username']);
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching donor name: " . $e->getMessage());
    echo "Error fetching donor information: " . $e->getMessage();
}

// Fetch donations with status and NGO information using prepared statement
$donations = [];
$totalDonations = 0;
$uniqueLocations = 0;
try {
    $stmt = $conn->prepare("
        SELECT f.fooddetails_id, f.food_name, f.food_type, f.food_category, f.quantity, f.unit, 
               f.pickup_address, f.donor_name, f.phone, f.alt_phone, f.image, f.status, f.assigned_ngo_id,
               CASE 
                   WHEN f.assigned_ngo_id = 'NGO NOT FOUND' THEN 'NGO NOT FOUND'
                   ELSE n.ngo_name 
               END as ngo_name,
               f.created_at
        FROM fooddetails f 
        LEFT JOIN ngo n ON f.assigned_ngo_id = n.ngo_id 
        WHERE f.user_id = ? 
        ORDER BY f.fooddetails_id DESC
    ");
    if ($stmt === false) {
        throw new Exception("Prepare failed for donations query: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
            // Track unique pickup locations
            if (!empty($row['pickup_address'])) {
                $locations[$row['pickup_address']] = true;
            }
        }
        $totalDonations = count($donations);
        $uniqueLocations = count($locations);
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching donations: " . $e->getMessage());
    echo "Error fetching donation data: " . $e->getMessage();
}

// Calculate statistics for different food categories
$categoryStats = [];
foreach ($donations as $donation) {
    $category = $donation['food_category'] ?? 'Unknown';
    if (!isset($categoryStats[$category])) {
        $categoryStats[$category] = 0;
    }
    $categoryStats[$category]++;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Donor Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #554e4e, #686157);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .nav-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
            z-index: 2;
            position: relative;
            opacity: 0;
            animation: slideDown 1s ease forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 2.2em;
            color: white;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo-text b {
            color: #34b409;
            font-weight: 700;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .welcome-message {
            font-size: 1.1em;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            opacity: 0.8;
        }

        .nav-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-tab.active {
            color: #ff6b6b;
            background: white;
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff6b6b, #ffa726);
        }

        .nav-tab:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .content {
            padding: 30px;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease-in;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .stat-card {
            text-align: center;
            background: linear-gradient(135deg, #66cbea, #4ba272);
            color: white;
        }

        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin: 15px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 10px;
            transition: width 1s ease;
        }

        .donation-history {
            max-height: 500px;
            overflow-y: auto;
        }

        .donation-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            border-left: 4px solid #28a745;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .donation-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .donation-details {
            flex: 1;
            padding-right: 15px;
        }

        .donation-details h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .donation-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .donation-meta-item {
            display: flex;
            align-items: center;
            font-size: 0.9em;
            color: #6c757d;
        }

        .donation-meta-item strong {
            color: #495057;
            margin-right: 5px;
            min-width: 80px;
        }

        .donation-status-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #dee2e6;
        }

        .donation-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #dee2e6;
            flex-shrink: 0;
        }

        /* Status styling */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-accepted {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-denied {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* NGO styling */
        .ngo-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .ngo-assigned {
            background-color: #e7f3ff;
            color: #0056b3;
            border: 1px solid #b3d9ff;
        }

        .ngo-not-found {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .ngo-not-assigned {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        .category-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .category-item {
            background: linear-gradient(135deg, #ffeaa7, #fab1a0);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            color: #2d3436;
            font-weight: 600;
        }

        .category-count {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            left:20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 3;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }

        .add-donation-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-donation-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2em;
            }

            .logo-text {
                font-size: 1.8em;
            }

            .nav-logo img {
                width: 40px;
                height: 40px;
            }

            .logout-btn {
                position: static;
                margin-bottom: 20px;
                display: inline-block;
            }

            .donation-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .donation-image {
                margin-top: 15px;
                align-self: center;
            }

            .donation-meta {
                grid-template-columns: 1fr;
            }

            .donation-status-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="home/homeSession.php" class="logout-btn"><< Back to home</a>
            <div class="nav-logo">
                <img src="./img/logo.png" alt="easyDonate Logo">
                <div class="logo-text">easy<b>Donate</b></div>
            </div>
            <?php if ($donor_name): ?>
                <div class="welcome-message">Welcome back, <?php echo $donor_name; ?>!</div>
            <?php endif; ?>
            <h1>Donor Dashboard</h1>
            <p>Making a difference, one meal at a time</p>
        </div>

        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showTab('dashboard')">Dashboard</button>
            <button class="nav-tab" onclick="showTab('history')">My Donations</button>
            <button class="nav-tab" onclick="showTab('stats')">Statistics</button>
        </div>

        <div class="content">
            
            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-content active">
                <a href="fooddetails.php" class="add-donation-btn">+ Add New Donation</a>

               

                
                <div class="dashboard-grid">
                    <div class="card stat-card">
                        <h3>Total Donations</h3>
                        <div class="stat-number" id="totalDonations"><?php echo $totalDonations; ?></div>
                        <p>Food items shared</p>
                    </div>
                    
                    <div class="card stat-card">
                        <h3>Pickup Locations</h3>
                        <div class="stat-number" id="uniqueLocations"><?php echo $uniqueLocations; ?></div>
                        <p>Areas served</p>
                    </div>
                    
                    <div class="card">
                        <h3>📊 Monthly Progress</h3>
                        <p><strong>This Month:</strong></p>
                        <div class="progress-bar">
                            <?php 
                            $monthlyGoal = 20;
                            $progressPercent = $totalDonations > 0 ? min(($totalDonations / $monthlyGoal) * 100, 100) : 0;
                            ?>
                            <div class="progress-fill" style="width: <?php echo $progressPercent; ?>%"></div>
                        </div>
                        <p><?php echo round($progressPercent); ?>% towards monthly goal (<?php echo $monthlyGoal; ?> items)</p>
                    </div>
                </div>
            </div>

            <!-- History Tab -->
            <div id="history" class="tab-content">
                <div class="card">
                    <h3>📋 My Donation History</h3>
                    <div class="donation-history" id="donationHistory">
                        <?php if (empty($donations)): ?>
                            <div class="no-data">
                                <h4>No donations yet</h4>
                                <p>Start making a difference today by donating food!</p>
                                <a href="fooddetails.php" class="add-donation-btn">Make Your First Donation</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($donations as $donation): ?>
                                <div class="donation-item">
                                    <div class="donation-details">
                                        <h4><?php echo htmlspecialchars($donation['food_name'] ?? 'Food Item'); ?></h4>
                                        
                                        <div class="donation-meta">
                                            <div class="donation-meta-item">
                                                <strong>Type:</strong> <?php echo htmlspecialchars($donation['food_type'] ?? 'N/A'); ?>
                                            </div>
                                            <div class="donation-meta-item">
                                                <strong>Category:</strong> <?php echo htmlspecialchars($donation['food_category'] ?? 'N/A'); ?>
                                            </div>
                                            <div class="donation-meta-item">
                                                <strong>Quantity:</strong> <?php echo htmlspecialchars($donation['quantity'] ?? '0') . ' ' . htmlspecialchars($donation['unit'] ?? ''); ?>
                                            </div>
                                            <div class="donation-meta-item">
                                                <strong>Contact:</strong> <?php echo htmlspecialchars($donation['phone'] ?? 'N/A'); ?>
                                            </div>
                                            <div class="donation-meta-item">
                                                <strong>Date:</strong> <?php echo htmlspecialchars($donation['created_at'] ?? 'N/A'); ?>
                                            </div>
                                            <div class="donation-meta-item">
                                                <strong>Pickup:</strong> <?php echo htmlspecialchars($donation['pickup_address'] ?? 'N/A'); ?>
                                            </div>
                                        </div>

                                        <div class="donation-status-row">
                                            <div>
                                                <strong>Status:</strong>
                                                <?php 
                                                $status = $donation['status'] ?? 'pending';
                                                echo "<span class='status-badge status-" . strtolower($status) . "'>" . ucfirst($status) . "</span>";
                                                ?>
                                            </div>
                                            <div>
                                                <strong>NGO:</strong>
                                                <?php 
                                                if ($donation['assigned_ngo_id'] === 'NGO NOT FOUND') {
                                                    echo "<span class='ngo-badge ngo-not-found'>NGO NOT FOUND</span>";
                                                } elseif (!empty($donation['ngo_name'])) {
                                                    echo "<span class='ngo-badge ngo-assigned'>" . htmlspecialchars($donation['ngo_name']) . "</span>";
                                                } else {
                                                    echo "<span class='ngo-badge ngo-not-assigned'>Not Assigned</span>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($donation['image']) && file_exists("uploads/" . basename($donation['image']))): ?>
                                        <img src="uploads/<?php echo basename($donation['image']); ?>" alt="Food Image" class="donation-image">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div id="stats" class="tab-content">
                <div class="card">
                    <h3>📈 Food Category Breakdown</h3>
                    <?php if (!empty($categoryStats)): ?>
                        <div class="category-stats">
                            <?php foreach ($categoryStats as $category => $count): ?>
                                <div class="category-item">
                                    <div class="category-count"><?php echo $count; ?></div>
                                    <div><?php echo htmlspecialchars($category); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-data">No donation statistics available yet.</div>
                    <?php endif; ?>
                </div>
                
                <div class="card" style="margin-top: 25px;">
                    <h3>💡 Quick Stats</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                        <div style="text-align: center; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                            <strong style="font-size: 1.5em; color: #1976d2;"><?php echo $totalDonations; ?></strong>
                            <p style="margin: 5px 0; color: #1976d2;">Total Items</p>
                        </div>
                        <div style="text-align: center; padding: 15px; background: #e8f5e8; border-radius: 8px;">
                            <strong style="font-size: 1.5em; color: #388e3c;"><?php echo count($categoryStats); ?></strong>
                            <p style="margin: 5px 0; color: #388e3c;">Categories</p>
                        </div>
                        <div style="text-align: center; padding: 15px; background: #fff3e0; border-radius: 8px;">
                            <strong style="font-size: 1.5em; color: #f57c00;"><?php echo $uniqueLocations; ?></strong>
                            <p style="margin: 5px 0; color: #f57c00;">Locations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from all nav tabs
            const navTabs = document.querySelectorAll('.nav-tab');
            navTabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab and mark nav tab as active
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling and animations
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Animate progress bar
            setTimeout(() => {
                const progressBar = document.querySelector('.progress-fill');
                if (progressBar) {
                    progressBar.style.width = progressBar.style.width;
                }
            }, 500);
        });

        // Add interactive hover effects
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click animation to donation items
        document.querySelectorAll('.donation-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateX(5px)';
                }, 100);
            });
        });
    </script>
</body>
</html>