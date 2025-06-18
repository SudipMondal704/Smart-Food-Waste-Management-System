<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_data = null;
$is_logged_in = false;

if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];
    
    if ($user_type == 'Donor') {
        $query = "SELECT username as name, email, phone, address, image, created_at FROM users WHERE user_id = ?";
    } elseif ($user_type == 'NGO') {
        $query = "SELECT ngo_name as name, email, phone, address, image, created_at FROM ngo WHERE ngo_id = ?";
    }
    
    if (isset($query)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            
            // Set default donation count for now
            $user_data['donation_count'] = 0;
            
            // Format join date
            $join_date = new DateTime($user_data['created_at']);
            $user_data['join_date'] = $join_date->format('M Y');
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>easyDonate - Save Food Share joy</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Flaticons -->
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <style>
        .service-section {
            padding: 50px 30px;
            align-items: center;
            background: whitesmoke;
        }

        .service-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .service-label {
            color: #2dd4bf;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .service-title {
            color: #2d2d2d;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .service-underline {
            width: 80px;
            height: 4px;
            background: #2dd4bf;
            margin: 0 auto;
        }

        /* Main Content Grid */
        .service-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
            margin-bottom: 60px;
        }

        .service-overview {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .service-overview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #2dd4bf, #34b409);
        }

        .service-overview h3 {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .service-overview h3 i {
            color: #34b409;
            font-size: 1.5rem;
        }

        .service-overview p {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .service-features {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .service-features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #34b409, #2dd4bf);
        }

        .service-features h3 {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .service-features h3 i {
            color: #2dd4bf;
            font-size: 1.5rem;
        }

        .service-services-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-services-list li {
            padding: 20px 0;
            padding-left: 50px;
            position: relative;
            font-size: 1.1rem;
            color: #475569;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            line-height: 1.6;
        }

        .service-services-list li:last-child {
            border-bottom: none;
        }

        .service-services-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 22px;
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #34b409, #2dd4bf);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(52, 180, 9, 0.3);
        }

        .service-services-list li::after {
            content: '✓';
            position: absolute;
            left: 7px;
            top: 22px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            z-index: 1;
        }

        .service-services-list li:hover {
            color: #1e293b;
            transform: translateX(10px);
            background: rgba(45, 212, 191, 0.05);
            border-radius: 10px;
            padding-right: 20px;
        }

        /* Characteristics Section */
        .characteristics-section {
            margin-top: 60px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .characteristics-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
        }

        .characteristics-title {
            color: #1e293b;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .characteristics-title i {
            color: #8b5cf6;
            font-size: 2rem;
        }

        .characteristics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .characteristic-item {
            background: rgba(255, 255, 255, 0.7);
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #34b409;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .characteristic-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(52, 180, 9, 0.05), rgba(45, 212, 191, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .characteristic-item:hover::before {
            opacity: 1;
        }

        .characteristic-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-left-color: #2dd4bf;
        }

        .characteristic-item h4 {
            color: #1e293b;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .characteristic-item p {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

    </style>
</head>
<body>
	<header>
        <div class="top-image" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(../img/team.jpg);">
        <!-- Navbar Section -->
            <nav class="navbar">
                <a href="#" class="nav-logo">
                    <img src="../img/logo.png" alt="Food Donate Logo">
                    <h2 class="logo-text">easy<b style="color: #34b409; font-weight: 600;"> Donate</b></h2>
                </a>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="homeSession.php" style="--i:1">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="About.php" style="--i:2">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" style="--i:3" class="active">Pages <i class="fas fa-chevron-down dropdown-icon"></i></a>
                        <div class="dropdown-content">
                            <a href="service.php" class="active">Service</a>
                            <a href="#">Donate</a>
                            <a href="team.php">Our Team</a>
                            <a href="voices-of-community.php">Voices of Community</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="Contact.php" style="--i:4">Contact</a>
                    </li>
                    
                    <?php if (!$is_logged_in): ?>
                    <li class="nav-item login-item">
                        <a href="../newlogin.php" style="--i:5" id="login-nav-btn">Login</a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- User Profile - Show only when logged in -->
            <?php if ($is_logged_in && $user_data): ?>
            <li class="nav-item user-profile active" id="user-profile">
                <?php 
                // Fix image path handling - since homeSession.php is in home/ folder
                $profile_image_src = '';
                if (!empty($user_data['image'])) {
                    // Correct path: homeSession.php is in home/, so uploaded_img/ is in same directory
                    $file_path = 'uploaded_img/' . htmlspecialchars($user_data['image']);
                    if (file_exists($file_path)) {
                        $profile_image_src = $file_path;
                    } else {
                        // Fallback to default user image if file doesn't exist
                        $profile_image_src = '../img/user.png';
                    }
                } else {
                    // Default placeholder for users without profile image
                    $profile_image_src = '../img/user.png';
                }
                
                // Same logic for popup image
                $popup_image_src = '';
                if (!empty($user_data['image'])) {
                    $file_path = 'uploaded_img/' . htmlspecialchars($user_data['image']);
                    if (file_exists($file_path)) {
                        $popup_image_src = $file_path;
                    } else {
                        $popup_image_src = '../img/user.png';
                    }
                } else {
                    $popup_image_src = '../img/user.png';
                }
            ?>
    
            <img src="<?php echo $profile_image_src; ?>" 
                alt="User Avatar" class="user-avatar" id="user-avatar" 
                onerror="this.src='../img/user.png'">
            
            <div class="profile-popup" id="profile-popup">
                <div class="profile-header">
                    <div class="image">
                        <img src="<?php echo $popup_image_src; ?>" 
                            alt="User Profile" id="profile-image"
                            onerror="this.src='../img/user.png'">
                    </div>
                    <div class="content">
                        <h3 id="profile-name"><?php echo htmlspecialchars($user_data['name']); ?></h3>
                        <p id="profile-email"><?php echo htmlspecialchars($user_data['email']); ?></p>
                    </div>
                </div>
                <div class="profile-info">
                    <div class="profile-info-item">
                        <i class="fas fa-user-tag"></i>
                        <span id="profile-type"> Type : <?php echo htmlspecialchars($_SESSION['user_type']); ?></span>
                    </div>
                    <div class="profile-info-item">
                        <?php if($user_type == 'Donor'): ?>
                            <i class='bx bxs-dashboard' ></i>
                            <span id="profile-type"><a href="../Donerpanel.php"> My Dashboard </a></span>
                        <?php elseif ($user_type == 'NGO'): ?>
                            <i class='bx bxs-dashboard' ></i>
                            <span id="profile-type"><a href="../NGOpanel.php"> My Dashboard </a></span>
                        <?php else: ?>
                        
                        <?php endif; ?>
                    </div>
                    <!-- Logout button moved to left side under other icons -->
                    <div class="profile-info-item logout-item" id="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </div>
                </div>
                <!-- Edit Profile button moved to bottom -->
                <div class="profile-actions">
                    <a href="update_profile.php" class="profile-btn edit-profile-btn">Edit Profile</a>
                </div>
            </div>
        </li>
        <?php endif; ?>
                </ul>
            </nav>
            <!-- Main Section -->
            <section class="content-main">
                <div class="sub-content">
                    <h2 class="title">Services</h2>
                    <p class="description">
                        Home > Pages > <a href="#" class="active">Services</a>
                    </p>
                </div>
            </section>
        </div>
        
        <!-- Services Section -->
            <div class="service-section">
                <div class="service-container">
                    <div class="service-header">
                        <div class="service-label">What services we are providing ?</div>
                        <h2 class="service-title">Services</h2>
                        <div class="service-underline"></div>
                    </div>

                    <div class="service-content">
                    <div class="service-overview">
                        <h3><i class="fas fa-heart"></i>Our Mission</h3>
                        <p>
                            The Smart Food Waste Reduction and Donation System is a revolutionary platform designed to bridge the gap between food abundance and food scarcity. We leverage cutting-edge technology to create a seamless connection between food donors and those in need.
                        </p>
                        <p>
                            Our system transforms how communities handle surplus food by providing an intelligent, automated platform that ensures no edible food goes to waste while addressing hunger and food insecurity in our society.
                        </p>
                        <p>
                            Through our  web platform, we've created a sustainable ecosystem where individuals, restaurants, event organizers, and businesses can easily donate excess food to verified NGOs and charitable organizations.
                        </p>
                    </div>

                    <div class="service-features">
                        <h3><i class="fas fa-cogs"></i>Key Features</h3>
                        <ul class="service-services-list">
                            <li>Connect donors with nearby verified NGOs and food banks instantly</li>
                            <li>Accept donations of cooked meals, raw ingredients, and packaged foods</li>
                            <li>Donor can register and submit food donation requests with details like quantity, type, and pickup address.</li>
                            <li>Admin can view all food requests in a centralized dashboard and assign them to NGOs based on location.</li>
                            <li>NGOs receive assigned food requests and manage pickups through their own dashboard.</li>
                            <li>All users (Donor, NGO, Admin) can track real-time food status (e.g., Pending, Assigned, Picked Up, Delivered).</li>
                            <li> NGO assignment system based on donor address and NGO availability.</li>
                            <li>Notification system  keeps users informed of request status and updates.</li>
                            <li>Donors can give feedback and ratings for NGO service after successful pickup.</li>
                            <li>Secure login system with role-based dashboards for Donors, NGOs, and Admin to ensure safe access and data handling.</li>
                        </ul>
                    </div>
                </div>

                <div class="characteristics-section">
                    <h2 class="characteristics-title">
                        <i class="fas fa-star"></i>
                        Upcoming Features
                    </h2>
                    <div class="characteristics-grid">
                        <div class="characteristic-item">
                            <h4>User-Friendly Interface</h4>
                            <p>Intuitive design with simple registration process for both donors and NGOs, ensuring seamless user experience across all platforms.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Real-Time Updates</h4>
                            <p>Instant alerts and notifications about food availability, pickup requests, and donation confirmations to keep all parties informed.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Geo-Mapping Integration</h4>
                            <p>Advanced GPS technology to locate and suggest the nearest NGOs, food banks, and collection points for optimal efficiency.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Quality & Safety Control</h4>
                            <p>Temperature monitoring systems and food safety protocols to ensure all donated food meets hygiene and quality standards.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Analytics Dashboard</h4>
                            <p>Comprehensive tracking of donations, food weight, environmental impact, and community contribution metrics.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Secure Login System</h4>
                            <p>Verified user authentication and background checks for NGOs to prevent misuse and ensure legitimate food distribution.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Scheduled Pickup Feature</h4>
                            <p>Pre-planned collection services for bulk donations and recurring food contributions from regular donors.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Smart Demand Matching</h4>
                            <p>AI-powered matching algorithm that connects food availability with NGO demand patterns for maximum efficiency.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Feedback & Rating System</h4>
                            <p>Community-driven reviews and ratings to maintain trust, quality, and continuous improvement of services.</p>
                        </div>
                        <div class="characteristic-item">
                            <h4>Multi-Language Support</h4>
                            <p>Accessible interface in multiple regional languages to serve diverse communities across different geographical areas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
            <footer class="footer-section">
                <div class="section-content">
                    <div class="footer-left">
                        <div class="footer-title">
                            <img src="../img/logo.png" alt="Food Donate Logo">
                            <h2 class="logo-text">easy <b style="color: green;">Donate</b></h2>
                        </div>
                            <p class="text">
                                The basic concept of this project <b style="color: green;">Food Waste Management</b>
                                is to collect the excess / leftover food from donors such as hotels, restaurants,
                                marriage halls, etc. and distribute to the needy people.
                                <span>Join us in our mission to reduce food waste and help those in need.</span>
                            </p>
                            <div class="button">
                                <a href="#" class="btn">Read More</a>
                            </div>
                        
                    </div>
                    <div class="footer-center">
                        <h2 class="footer-title">Quick Links</h2>
                        <ul class="quick-list">
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="About.php">About Us</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="Contact.php">Contact Us</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="service.php">Service</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="team.php">Our Team</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="voices-of-community.php">Voices of Community</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-right">
                        <h2 class="footer-title">Contact Us</h2>
                        <ul class="contact-list">
                            <li class="contact-info">
                                <i class="fa-solid fa-location-dot"></i>
                                <p>Dewandighi, Katwa Road, Purba Bardhaman, 713102</p>
                            </li>
                            <li class="contact-info">
                                <i class="fa-solid fa-envelope"></i>
                                <p>fooddonate@gmail.com</p>
                            </li>
                            <li class="contact-info">
                                <i class="fa-solid fa-phone"></i>
                                <p>(+91) 0000 000 000</p>
                            </li>
                            <li class="contact-info">
                                <i class="fa-regular fa-clock"></i>
                                <p>Monday - Sunday : 24 x 7 Opened</p>
                            </li>
                            <li class="contact-info">
                                <i class="fa-solid fa-globe"></i>
                                <p>www.fooddonate.com</p>
                            </li>
                        </ul>
                        <ul class="social">
                            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="copyright">
                    <p> © Copyright 2025 easy Donate. All rights reserved.</p>
                </div>
            </footer>

            <!-- Back to Top Scrollbar -->
            <button class="scroll-to-top" id="scrollToTop">
                <i class="fas fa-chevron-up"></i>
            </button>
  </header>
  <script src="../js/script.js"></script>
  <script>
    // User Profile Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const userAvatar = document.getElementById('user-avatar');
        const profilePopup = document.getElementById('profile-popup');
        const logoutBtn = document.getElementById('logout-btn');
        const userProfile = document.getElementById('user-profile');
        
        // Toggle profile popup
        if (userAvatar && profilePopup) {
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                profilePopup.classList.toggle('show');
            });
        }
        
        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (profilePopup && userProfile && !userProfile.contains(e.target)) {
                profilePopup.classList.remove('show');
            }
        });
        
        // Logout functionality
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to logout?')) {
                    // Redirect to logout script
                    window.location.href = 'logout.php';
                }
            });
        }
    });
  </script>
</body>
</html>