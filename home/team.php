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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .team-section {
            width: 100vw;
            min-height: 100vh;
            margin: 0;
            padding: 80px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        /* Add a subtle pattern overlay */
        .team-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 25% 25%, rgba(45, 212, 191, 0.1) 0%, transparent 25%),
                radial-gradient(circle at 75% 75%, rgba(150, 201, 63, 0.1) 0%, transparent 25%);
            pointer-events: none;
        }

        .team-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        .team-label {
            color: #2dd4bf;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
        }

        .team-title {
            color: #282828;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .team-underline {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #2dd4bf, #96c93f);
            margin: 0 auto;
            border-radius: 2px;
            box-shadow: 0 2px 8px rgba(45, 212, 191, 0.3);
        }

        .team-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            width: 90%;
            justify-items: center;
            position: relative;
            z-index: 2;
        }

        .team-card {
            background: linear-gradient(135deg, #ffffff, #e9f2fb);
            border-radius: 20px;
            padding: 25px 20px;
            text-align: center;
            width: 100%;
            max-width: 350px;
            box-shadow: 
                0 15px 35px rgba(0,0,0,0.2);
                /* 0 5px 15px rgba(0,0,0,0.06); */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.9);
            
        }

        .team-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 
                0 25px 50px rgba(0,0,0,0.15),
                0 8px 20px rgba(45, 212, 191, 0.2);
        }

        .profile-image {
            width: 160px;
            height: 160px;
            margin: 0 auto 25px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #f7f6f6, #f5f5f5);
            border: 4px solid #f0f0f0;
            transition: all 0.3s ease;
            position: relative;
        }

        .team-card:hover .profile-image {
            border-color: #2dd4bf;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(45, 212, 191, 0.3);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            align-items: center;
            transition: filter 0.3s ease;
        }

        .team-card:hover .profile-image img {
            filter: grayscale(0%);
        }

        .name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .position {
            font-size: 1rem;
            color: #96c93f;
            font-style: italic;
            font-weight: 500;
            margin-bottom: 20px;
            text-transform: capitalize;
            padding: 0 10px;
        }

        .description {
            font-size: 0.95rem;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align:start;
            padding: 0 5px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.1s;
        }

        .team-card:hover .social-icons {
            opacity: 1;
            transform: translateY(0);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #4a5568, #2d3748);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .social-icon:hover {
            background: linear-gradient(135deg, #96c93f, #2dd4bf);
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 16px rgba(150, 201, 63, 0.3);
        }

        /* Hover effect overlay */
        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(45, 212, 191, 0.05), 
                transparent);
            transition: left 0.6s ease;
        }

        .team-card:hover::before {
            left: 100%;
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
                    <h2 class="logo-text">easy<b style="color: #34b409;">Donate</b></h2>
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
                            <a href="service.html">Service</a>
                            <a href="#">Donate</a>
                            <a href="team.php" class="active">Our Team</a>
                            <a href="voices-of-community.html">Voices of Community</a>
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
                         $profile_image_src = '';
                        if (!empty($user_data['image'])) {
                            $file_path = 'uploaded_img/' . htmlspecialchars($user_data['image']);
                            if (file_exists($file_path)) {
                                $profile_image_src = $file_path;
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
                                <img src="<?php echo $popup_image_src; ?>" 
                                     alt="User Profile" id="profile-image"
                                     onerror="this.src='../img/user.png'">
                                <h3 id="profile-name"><?php echo htmlspecialchars($user_data['name']); ?></h3>
                                <p id="profile-email"><?php echo htmlspecialchars($user_data['email']); ?></p>
                            </div>
                            <div class="profile-info">
                                <div class="profile-info-item">
                                    <i class="fas fa-phone"></i>
                                    <span id="profile-phone"><?php echo htmlspecialchars($user_data['phone']); ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="profile-location"><?php echo htmlspecialchars($user_data['address']); ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-calendar"></i>
                                    <span id="profile-joined">Joined: <?php echo $user_data['join_date']; ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-heart"></i>
                                    <span id="profile-donations">Donations: <?php echo $user_data['donation_count']; ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-user-tag"></i>
                                    <span id="profile-type">Type: <?php echo htmlspecialchars($_SESSION['user_type']); ?></span>
                                </div>
                                
                                <div class="profile-info-item logout-item" id="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </div>
                            </div>
                            
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
                    <h2 class="title">Our Team</h2>
                    <p class="description">
                        Home > Pages > <a href="#" class="active">Our Team</a>
                    </p>
                </div>
            </section>
        </div>
        
        <!-- Team Section -->
        <section class="team-section">
            <div class="team-header">
                <span class="team-label">Team Members & Founders</span>
                <h2 class="team-title">Let's Meet With Our Leaders and Members</h2>
                <div class="team-underline"></div>
            </div>

            <div class="team-container">
                <div class="team-card">
                    <div class="profile-image">
                        <img src="../img/Anjan.jpg">
                    </div>
                    <div class="name">Anjan Saha</div>
                    <div class="position">Founder & CEO</div>
                    <div class="description">
                        Passionate advocate for food security with 12+ years in non-profit leadership. Established easyDonate to bridge the gap between food surplus and hunger, transforming how communities approach food waste management.
                    </div>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook">f</a>
                        <a href="#" class="social-icon twitter">𝕏</a>
                        <a href="#" class="social-icon instagram">📷</a>
                        <a href="#" class="social-icon linkedin">in</a>
                    </div>
                </div>

                <div class="team-card">
                    <div class="profile-image">
                        <img src="../img/sudip.jpg">
                    </div>
                    <div class="name">Sudip Mondal</div>
                    <div class="position">Food Recovery Coordinator</div>
                    <div class="description">
                        Expert in logistics and food safety protocols, managing partnerships with restaurants, hotels, and catering services. Ensures safe collection and distribution of surplus food to maximize impact and minimize waste.
                    </div>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook">f</a>
                        <a href="#" class="social-icon twitter">𝕏</a>
                        <a href="#" class="social-icon instagram">📷</a>
                        <a href="#" class="social-icon linkedin">in</a>
                    </div>
                </div>

                <div class="team-card">
                    <div class="profile-image">
                        <img src="../img/rayantan.jpg">
                    </div>
                    <div class="name">Rayantan Das</div>
                    <div class="position">MD of our Organization</div>
                    <div class="description">
                        Dedicated social worker connecting with local communities, NGOs, and vulnerable populations. Identifies families in need and ensures equitable distribution of donated food while building lasting community partnerships.
                    </div>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook">f</a>
                        <a href="#" class="social-icon twitter">𝕏</a>
                        <a href="#" class="social-icon instagram">📷</a>
                        <a href="#" class="social-icon linkedin">in</a>
                    </div>
                </div>

                <div class="team-card">
                    <div class="profile-image">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Cdefs%3E%3ClinearGradient id='bg3' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23fce7f3'/%3E%3Cstop offset='100%25' style='stop-color:%23fbcfe8'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='200' fill='url(%23bg3)'/%3E%3Cpath d='M100 70c-20 0-36 16-36 36s16 36 36 36 36-16 36-36-16-36-36-36z' fill='%23ec4899'/%3E%3Cpath d='M100 142c-28 0-50 22-50 50v8h100v-8c0-28-22-50-50-50z' fill='%23ec4899'/%3E%3C/svg%3E" alt="Ann Richmond">
                    </div>
                    <div class="name">Rintu Ghosh</div>
                    <div class="position">Community Outreach Manager</div>
                    <div class="description">
                        Dedicated social worker connecting with local communities, NGOs, and vulnerable populations. Identifies families in need and ensures equitable distribution of donated food while building lasting community partnerships.
                    </div>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook">f</a>
                        <a href="#" class="social-icon twitter">𝕏</a>
                        <a href="#" class="social-icon instagram">📷</a>
                        <a href="#" class="social-icon linkedin">in</a>
                    </div>
                </div>
            </div>
        </section>

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
                                <a href="#">About Us</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="#">Contact Us</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="#">Service</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="#">Our Team</a>
                            </li>
                            <li class="refer">
                                <i class="fas fa-chevron-right dropdown-icon"></i>
                                <a href="#">Voices of Community</a>
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
                    <p> © Copyright 2025 Food Donate. All rights reserved.</p>
                </div>
            </footer>

            <!-- Back to Top Scrollbar -->
            <button class="scroll-to-top" id="scrollToTop">
                <i class="fas fa-chevron-up"></i>
            </button>
  </header>
  
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
  <script src="../js/script.js"></script>
</body>
</html>