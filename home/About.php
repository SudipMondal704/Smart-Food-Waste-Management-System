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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: #dbf9eb;
        }

        .about-about-section-page {
            padding: 80px 20px;
            min-height: 100vh;
        }

        .about-about-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .about-section-row {
            display: flex;
            align-items: center;
            margin-bottom: 70px;
            min-height: 500px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .about-section-row:nth-child(even) {
            flex-direction: row-reverse;
        }

        .about-section-row.animate-in {
            opacity: 1;
            transform: translateY(0);
        }

        .about-text-content {
            flex: 1;
            padding: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateX(-30px);
        }

        .about-section-row:nth-child(even) .about-text-content {
            transform: translateX(30px);
        }

        .about-section-row.animate-in .about-text-content {
            opacity: 1;
            transform: translateX(0);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.2s;
        }

        .about-text-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .about-text-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff4545, #eaed47,#6ecd4e, #96ceb4);
            background-size: 300% 100%;
            animation: aboutGradientShift 3s ease infinite;
        }

        .about-image-content {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transform: scale(0.8);
        }

        .about-section-row.animate-in .about-image-content {
            opacity: 1;
            transform: scale(1);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.4s;
        }

        .about-image-wrapper {
            position: relative;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transition: transform 0.4s ease;
            max-width: 500px;
            width: 100%;
        }

        .about-image-wrapper:hover {
            transform: scale(1.05) rotate(1deg);
        }

        .about-image-wrapper img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .about-image-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 60%, rgba(255, 255, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .about-image-wrapper:hover::after {
            opacity: 1;
        }

        .about-text-content h1 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            line-height: 1.2;
            opacity: 0;
            transform: translateY(20px);
        }

        .about-text-content h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            position: relative;
            line-height: 1.3;
            opacity: 0;
            transform: translateY(20px);
        }

        .about-text-content p {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.5;
            text-align: justify;
            opacity: 0;
            transform: translateY(20px);
        }

        .about-section-row.animate-in .about-text-content h1,
        .about-section-row.animate-in .about-text-content h2 {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.4s;
        }

        .about-section-row.animate-in .about-text-content p {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.6s;
        }

        .about-services-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .about-services-list li {
            padding: 15px 0;
            padding-left: 30px;
            position: relative;
            font-size: 1.1rem;
            color: #555;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: color 0.3s ease;
            opacity: 0;
            transform: translateX(-20px);
        }

        .about-section-row.animate-in .about-services-list li {
            opacity: 1;
            transform: translateX(0);
        }

        .about-section-row.animate-in .about-services-list li:nth-child(1) {
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) 1.0s;
        }

        .about-section-row.animate-in .about-services-list li:nth-child(2) {
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) 1.1s;
        }

        .about-section-row.animate-in .about-services-list li:nth-child(3) {
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) 1.2s;
        }

        .about-section-row.animate-in .about-services-list li:nth-child(4) {
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) 1.3s;
        }

        .about-services-list li:hover {
            color: #667eea;
        }

        .about-services-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 15px;
            color: #4ecdc4;
            font-weight: bold;
            font-size: 1.3rem;
        }

        .about-cta-section {
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            color: white;
            margin-top: 80px;
            box-shadow: 0 30px 60px rgba(102, 126, 234, 0.3);
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .about-cta-section.animate-in {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .about-cta-section h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .about-cta-section p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.8);
        }
        .about-cta-button {
            display: inline-block;
            padding: 16px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .about-cta-button:hover {
            transform: translateY(-3px);
            background: #667eea;
            color: white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        @keyframes aboutFadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aboutGradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Floating animation for images */
        .about-image-wrapper {
            animation: aboutFloat 6s ease-in-out infinite;
        }

        .about-section-row:nth-child(even) .about-image-wrapper {
            animation-delay: 1s;
        }

        @keyframes aboutFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(0.5deg); }
        }
    </style>
</head>
<body>
	<header>
        <div class="top-image" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(../img/About.jpg);">
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
                        <a href="About.php" style="--i:2" class="active">About</a>
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
                    <h2 class="title">About Us</h2>
                    <p class="description">
                        Home > <a href="#" class="active">About Us</a>
                    </p>
                </div>
            </section>
        </div>
        <!-- Enhanced About Us Section from first HTML -->
        <section class="about-about-section-page" id="about">
            <div class="about-about-container">

                <!-- Intro Section -->
                <div class="about-section-row">
                    <div class="about-text-content">
                        <h1>About Us</h1>
                        <p>We are a dedicated team working towards eradicating hunger and reducing food waste across India through technology and community support. We connect food donors with NGOs to help feed those in need, creating a bridge between abundance and necessity.</p>
                    </div>
                    <div class="about-image-content">
                        <div class="about-image-wrapper">
                            <img src="../img/About-us.jpg" alt="About Us - Food Donation">
                        </div>
                    </div>
                </div>

                <!-- Mission Section -->
                <div class="about-section-row">
                    <div class="about-text-content">
                        <h2>Our Mission</h2>
                        <p>Our mission is to create a smart and efficient platform that bridges the gap between food donors and the needy. We aim to make food donation quick, accessible, and impactful—ensuring that surplus food reaches the hungry instead of going to waste. Through innovative technology and community partnerships, we're building a sustainable solution to hunger.</p>
                    </div>
                    <div class="about-image-content">
                        <div class="about-image-wrapper">
                            <img src="../img/OurMission.png" alt="Our Mission - Helping Communities">
                        </div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="about-section-row">
                    <div class="about-text-content">
                        <h2>Our Services</h2>
                        <p>We provide comprehensive solutions to connect donors with those in need:</p>
                        <ul class="about-services-list">
                            <li>Connect donors (individuals, families, event organizers, restaurants) with nearby NGOs</li>
                            <li>Allow donors to donate excess cooked, raw, or packaged food from events and daily life</li>
                            <li>Enable NGOs to receive donation requests and collect food directly from donor locations</li>
                            <li>Ensure food is safely distributed to poor, needy, and street-hungry individuals</li>
                        </ul>
                    </div>
                    <div class="about-image-content">
                        <div class="about-image-wrapper">
                            <img src="../img/services.png" alt="Our Services - Food Distribution">
                        </div>
                    </div>
                </div>
                    
                <!-- Goal Section -->
                <div class="about-section-row">
                    <div class="about-text-content">
                        <h2>Our Goal</h2>
                        <p>Every day, tons of edible food is wasted while millions go hungry. Our platform was built to solve both these problems—fighting hunger and reducing waste—with one simple solution. We believe that technology can be a powerful force for social good, creating connections that transform lives and communities.</p>
                    </div>
                    <div class="about-image-content">
                        <div class="about-image-wrapper">
                            <img src="../img/goal.png" alt="Our Goal - Zero Waste">
                        </div>
                    </div>
                </div>

                <!-- Vision Section -->
                <div class="about-section-row">
                    <div class="about-text-content">
                        <h2>Our Vision</h2>
                        <p>We envision an India where hunger is no longer a daily struggle and every meal counts. Our goal is to ensure that no edible food is thrown away while millions go without a meal. By building a strong network of donors and NGOs, we strive to create a system where excess food is effectively redirected to those who need it most. We dream of a future where no food is wasted and no one sleeps hungry.</p>
                    </div>
                    <div class="about-image-content">
                        <div class="about-image-wrapper">
                            <img src="../img/vision.png" alt="Our Vision - Bright Future">
                        </div>
                    </div>
                </div>

                <!-- Get Involved Section -->
                <div class="about-cta-section">
                    <h2>Get Involved</h2>
                    <p>Whether you are an individual, a restaurant, or an NGO, you can play a vital role in our mission. Donors can share their excess food, and NGOs can help deliver it to those who need it the most. Your small act of kindness can make a big difference in someone's life.</p>
                    <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/newlogin.php" class="about-cta-button">Join Us Today</a>
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
  <script>
    // Viewport animation observer
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe all about sections
    document.addEventListener('DOMContentLoaded', () => {
      const aboutSections = document.querySelectorAll('.about-section-row, .about-cta-section');
      aboutSections.forEach(section => {
        observer.observe(section);
      });
    });
  </script>
</body>
</html>