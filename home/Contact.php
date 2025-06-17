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
            
            // Count donations - you can uncomment and modify this when you have a donations table
            /*
            if ($user_type == 'Donor') {
                $donation_count_query = "SELECT COUNT(*) as donation_count FROM donations WHERE user_id = ?";
            } else {
                $donation_count_query = "SELECT COUNT(*) as donation_count FROM received_donations WHERE ngo_id = ?";
            }
            $donation_stmt = $conn->prepare($donation_count_query);
            $donation_stmt->bind_param("i", $user_id);
            $donation_stmt->execute();
            $donation_result = $donation_stmt->get_result();
            $donation_data = $donation_result->fetch_assoc();
            $user_data['donation_count'] = $donation_data['donation_count'];
            $donation_stmt->close();
            */
            
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
        /* Contact Page -  STYLES */
        .contact-section {
            padding: 80px 0 100px;
            background: #fff;
        }

        .contact-section .section-content {
            display: flex;
            gap: 80px;
            align-items: flex-start;
            justify-content: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-left {
            flex: 1;
            max-width: 650px;
        }

        .contact-section h2 {
            font-size: 32px;
            color: #333;
            margin-bottom: 40px;
            line-height: 1.3;
        }

        .contact-form {
            width: 50%;
        }

        .contact-form .form-input {
            width: 100%;
            max-width: 650px;
            height: 50px;
            padding: 0 15px;
            outline: none;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease;
        }

        .contact-form .form-input:focus {
            border-color: #34b409;
        }

        .contact-form textarea.form-input {
            height: 120px;
            padding: 15px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
        }

        /* Map Section - FIXED */
        .map-container {
            flex: 1;
            max-width: 400px;
        }

        .map-wrapper {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Accordion styles */
        .help-content {
            display: flex;
            gap: 30px;
        }
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
            margin-bottom: 5px;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            position: relative;
        }

        .accordion:after {
            content: '\f107'; /* Font Awesome arrow down icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #777;
            float: right;
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .accordion:hover {
            background-color: #34b409;
            color: #fff;
        }

        .accordion:hover:after {
            color: #fff;
        }

        .accordion.active {
            background-color: #34b409;
            color: rgb(230, 228, 228);
        }

        .accordion.active:after {
            content: '\f106'; /* Font Awesome arrow up icon */
            color: white;
            transform: rotate(0deg); /* Remove rotation since we're using different icons */
        }

        .panel {
            padding: 0;
            display: none;
            background-color: white;
            overflow: hidden;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
            border-top: none;
            margin-bottom: 10px;
        }

        .panel.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .panel p {
            padding: 5px 8px;
            margin: 0;
            line-height: 1.6;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
        }

        .panel a {
            color: #34b409;
            text-decoration: none;
            font-weight: 500;
        }

        .panel a:hover {
            text-decoration: underline;
        }

        /* Animation for smooth panel opening */
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 200px;
            }
        }

        /* Chatbot Section */
        .chatbot-section {
            padding: 30px;
            background-image: url(../img/footer-map.png);
            align-items: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-color: #516781;
        }

        .chatbot-section h2 {
            font-size: 23px;
            text-align: center;
            margin-bottom: 30px;
            color:rgb(247, 247, 247);
            font-size: 28px;
            font-weight: 600;
        }

        /* Fixed Chatbot Styles */
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-bottom: 30px;
        }

        .chat {
            height: 400px;
            width: 60vw;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            background: rgb(205, 252, 204);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .chat-header {
            background-color: #95b320;
            color: white;
            text-align: center;
            padding: 15px;
            font-weight: 600;
        }

        .messages {
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: 100%;
            width: 100%;
            padding: 15px;
            gap: 10px;
        }

        .messages::-webkit-scrollbar {
            width: 6px;
        }

        .messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .messages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .response {
            display: flex;
            align-items: flex-start;
            margin: 5px 0;
            gap: 10px;
        }
        .response.user {
            flex-direction: row-reverse;
        }
        .response.user .message {
            background: #e340b2;
            color: white;
            margin-left: auto;
        }
        .response.bot .message {
            background: rgb(65, 218, 242);
            color: #333;
            border: 1px solid #e0e0e0;
             margin-right: auto;
        }
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            color: #666;
        }
        .chat-input-container {
            padding: 15px;
        }
        .chat input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        .chat input:focus {
            border-color: #000000;
        }
    </style>

</head>
<body>
	<header>
        <div class="top-image" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(../img/Contact.jpg);">
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
                    <a href="#" style="--i:3">Pages <i class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="service.html">Service</a>
                        <a href="#">Donate</a>
                        <a href="team.php">Our Team</a>
                        <a href="voices-of-community.html">Voices of Community</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="Contact.php" style="--i:4" class="active">Contact</a>
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
                    <h2 class="title">Contact Us</h2>
                    <p class="description">
                        Home > <a href="#" class="active">Contact Us</a>
                    </p>
                </div>
            </section>
        </div>
        <!-- Contact Section -->
        <section class="contact-section">
                <div class="section-content">
                    <div class="contact-left">
                        <h2>If You Have Any Query,<br> Please Contact Us</h2>
                        <form action="#" class="contact-form">
                            <input type="text" placeholder="Your Name" class="form-input" required>
                            <input type="email" placeholder="Your Email" class="form-input" required>
                            <textarea placeholder="Your Message" class="form-input" required></textarea>
                            <button class="btn">Send Message <i class="fa-solid fa-circle-arrow-right"></i></button>
                        </form>
                    </div>
                    <div class="map-container">
                        <h2>Find Us Here</h2>
                        <div class="map-wrapper">
                            <iframe
                                src="https://maps.google.com/maps?q=Burdwan+Institute+Management+%26+Computer+Science&output=embed"
                                loading="lazy"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </section>

            <!-- chatbot -->
            <section class="chatbot-section">
            <h2>ChatBot Support <i class="fa-solid fa-robot"></i></h2>
            <div class="container">
                <div class="chat">
                    <div class="chat-header">
                        <h3>Food Donate Support Bot</h3>
                    </div>
                    <div class="messages" id="messages">
                        <div class="response bot">
                            <div></div>
                         </div>
                    </div>
                    <div class="chat-input-container">
                        <input id="input" type="text" placeholder="Type your message here..." autocomplete="off" />
                    </div>
                </div>

                <div class="help">
                    <h2 style="font-size: 23px; text-align: center; padding:10px;">Help & FAQs?</h2>
                    <div class="help-content">
                        <div class="help-left">
                            <button class="accordion">How to donate food ?</button>
                            <div class="panel">
                                <p style="font-size: 15px;">1. Click on <a href="homeSession.php">Donate Food</a> in Home Page </p>
                                <p style="font-size: 15px;">2. Fill the details </p>
                                <p style="font-size: 15px;">3. Click on submit</p>
                                
                            </div>
                            <button class="accordion">How will my donation be used?</button>
                            <div class="panel">
                            <p style="padding: 10px; font-size: 15px;">Your donation will be used to support our mission and the various programs 
                                and initiatives that we have in place. Your donation will help us to continue providing assistance 
                                and support to those in need. You can find more information about our programs and initiatives on our website.
                                If you have any specific questions or concerns, please feel free to contact us</p>
                            </div>
                            <button class="accordion">What should I do if my food donation is near or past its expiration date?</button>
                            <div class="panel">
                            <p style="padding: 10px; font-size: 15px;">We appreciate your willingness to donate, but to ensure the safety of our clients 
                                we can't accept food that is near or past its expiration date. We recommend checking expiration dates
                                before making a donation or contact us for further guidance</p>
                            </div>
                        </div>
                        <div class="help-right">
                                <button class="accordion">How to donate food ?</button>
                            <div class="panel">
                                <p style="font-size: 15px;">1. Click on <a href="homeSession.php">Donate Food</a> in Home Page </p>
                                <p style="font-size: 15px;">2. Fill the details </p>
                                <p style="font-size: 15px;">3. Click on submit</p>
                                <img src=" " alt="" width="100%">
                            </div>
                            <button class="accordion">How will my donation be used?</button>
                            <div class="panel">
                            <p style="padding: 10px; font-size: 15px;">Your donation will be used to support our mission and the various programs 
                                and initiatives that we have in place. Your donation will help us to continue providing assistance 
                                and support to those in need. You can find more information about our programs and initiatives on our website.
                                If you have any specific questions or concerns, please feel free to contact us</p>
                            </div>
                            <button class="accordion">What should I do if my food donation is near or past its expiration date?</button>
                            <div class="panel">
                            <p style="padding: 10px; font-size: 15px;">We appreciate your willingness to donate, but to ensure the safety of our clients 
                                we can't accept food that is near or past its expiration date. We recommend checking expiration dates
                                before making a donation or contact us for further guidance</p>
                            </div>
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