<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and get user data
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Save Food Share joy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        /* User Profile Styles */
        .user-profile {
            position: relative;
            display: none; /* Hidden by default, shown after login */
        }
        
        .user-profile.active {
            display: block;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #34b409;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover {
            transform: scale(1.05);
            border-color: #fff;
        }
        
        .profile-popup {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 20px;
            min-width: 280px;
            z-index: 1000;
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .profile-popup.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .profile-popup::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid white;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #34b409;
            object-fit: cover;
        }
        
        .profile-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .profile-header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .profile-info {
            margin-bottom: 20px;
        }
        
        .profile-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .profile-info-item i {
            width: 20px;
            color: #34b409;
            margin-right: 10px;
        }
        
        .profile-info-item span {
            color: #333;
            font-size: 14px;
        }
        
        /* Logout button styled as info item */
        .profile-info-item.logout-item {
            cursor: pointer;
            padding: 12px 0;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .profile-info-item.logout-item:hover {
            background-color: #fff5f5;
        }
        
        .profile-info-item.logout-item i {
            color: #dc3545;
        }
        
        .profile-info-item.logout-item span {
            color: #dc3545;
            font-weight: 500;
        }
        
        .profile-actions {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        
        .profile-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
        }
        
        .edit-profile-btn {
            background: #34b409;
            color: white;
        }
        
        .edit-profile-btn:hover {
            background: #2a9206;
        }
        
        /* Hide login nav item when user is logged in */
        .nav-menu .nav-item.login-item {
            display: list-item;
        }
        
        .nav-menu .nav-item.login-item.hidden {
            display: none !important;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-popup {
                right: -20px;
                min-width: 250px;
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Content -->
    <!-- Slideshow background -->
     <div class="background-slideshow">
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
    </div>
    <div class="background-overlay"></div>
    <header>
        <!-- Navbar Section -->
        <nav class="navbar">
            <a href="#" class="nav-logo">
                <img src="../img/logo.png" alt="Food Donate Logo">
                <h2 class="logo-text">easy<b style="color: #34b409;">Donate</b></h2>
            </a>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" style="--i:1" class="active">Home</a>
                </li>
                <li class="nav-item">
                    <a href="About.html" style="--i:2">About</a>
                </li>
                <li class="nav-item">
                    <a href="#" style="--i:3">Pages</a>
                </li>
                <li class="nav-item">
                    <a href="Contact.html" style="--i:4">Contact</a>
                </li>
                <!-- Login Link - Show only when not logged in -->
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
            // Fallback to placeholder if file doesn't exist
            $profile_image_src = 'https://via.placeholder.com/40x40/34b409/ffffff?text=' . strtoupper(substr($user_data['name'], 0, 2));
        }
    } else {
        // Default placeholder for users without profile image
        $profile_image_src = 'https://via.placeholder.com/40x40/34b409/ffffff?text=' . strtoupper(substr($user_data['name'], 0, 2));
    }
    
    // Same logic for popup image
    $popup_image_src = '';
    if (!empty($user_data['image'])) {
        $file_path = 'uploaded_img/' . htmlspecialchars($user_data['image']);
        if (file_exists($file_path)) {
            $popup_image_src = $file_path;
        } else {
            $popup_image_src = 'https://via.placeholder.com/80x80/34b409/ffffff?text=' . strtoupper(substr($user_data['name'], 0, 2));
        }
    } else {
        $popup_image_src = 'https://via.placeholder.com/80x80/34b409/ffffff?text=' . strtoupper(substr($user_data['name'], 0, 2));
    }
    ?>
    
    <img src="<?php echo $profile_image_src; ?>" 
         alt="User Avatar" class="user-avatar" id="user-avatar" 
         onerror="this.src='https://via.placeholder.com/40x40/34b409/ffffff?text=<?php echo strtoupper(substr($user_data['name'], 0, 2)); ?>'">
    
    <div class="profile-popup" id="profile-popup">
        <div class="profile-header">
            <img src="<?php echo $popup_image_src; ?>" 
                 alt="User Profile" id="profile-image"
                 onerror="this.src='https://via.placeholder.com/80x80/34b409/ffffff?text=<?php echo strtoupper(substr($user_data['name'], 0, 2)); ?>'">
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
        </nav>
        <main>
            <!-- Main Section -->
            <section class="main-section">
                <div class="section-content">
                    <div class="main-details">
                        <h1 class="title">
                            Let's Make The Change
                            <span>"Save Food, Share Joy!!"</span>
                        </h1>
                        <p class="description">
                            Cutting food waste is a delicious way of saving money, helping to feed the world and protect the planet.
                            Donate extra food to needy people because giving is not charity, it is an expression of humanity.
                            Together, we can build a world where no food goes to waste and no one goes hungry.
                        </p>
                    </div>
                    <div class="button">
                        <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/fooddetails.php" class="btn" id="donateNowBtn">
                            Donate Food<i class="fa-solid fa-circle-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </section>
            <!-- About Section -->
            <section class="about-section">
                <div class="section-content">
                    <div class="about-details">
                        <h2 class="section-title">About Us</h2>
                        <p class="text">We are a dedicated team working towards eradicating hunger and reducing food waste across India through technology and community support,we connect food donors with NGOs to help feed those in need.</p>
                        <div class="button">
                            <a href="About.html" class="btn">Read More<i class="fa-solid fa-circle-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="about-image-wrapper">
                        <img src="../img/About-us.jpg" alt="About" class="about-image">
                    </div>
                </div>
            </section>
            <!-- Ambition Section -->
             <div class="ambitions-section section">
                <p class="sub-heading">Look what we can do together.</p>
                <h2 class="heading">Our Ambitions</h2>
                <div class="cards-container">
                    <div class="card">
                        <div class="card__image">
                            <img src="../img/Reduce.jpg" alt="Reduce Food Waste">
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b style="color: #3c973f; font-size: 21px;">REDUCE</b> THE AMOUNT OF FOOD WASTE GENERATED</span>
                            <p class="card__text">Reducing food loss and waste is essential in a country where millions of people go hungry every day. When we reduce waste, we respect every food. It's up to us to change our habits to not wasting food and help others in doing the same.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <img src="../img/Check-Donate.jpg" alt="Check and Donate Food">
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b style="color: #3c973f; font-size: 21px;">CHECK</b> AND <b style="color: #3c973f; font-size: 21px;">DONATE</b> NUTRITIOUS FOOD TO NEEDY PEOPLE SAFELY AND SECURELY</span>
                            <p class="card__text">Some generated food waste is safe to eat, and can be donated to different food banks, and anti hunger organizations, approaching to provide nutritious food to people in need.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <img src="../img/Recycle.jpg" alt="Recycle Food Waste">
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b style="color: #3c973f; font-size: 21px;">RECYCLE</b> ALL THE UNAVOIDABLE FOOD WASTE PREVENTING IT FROM LANDFILLS</span>
                            <p class="card__text">For food waste, a landfill is the end of the life cycle of the food but when de-composed it can be recycled into soil or energy.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- How Does It Work Section -->
            <div class="how-it-works-section section">
                <div class="container">
                    <h2 class="section-title">How does easyDonate works?</h2>
                    
                    <div class="steps-container">
                        <div class="step">
                            <div class="step-number">1</div>
                            <h3 class="step-title">Food is <b style="color: #3c973f">DONATED</b></h3>
                            <p class="step-description">Farms, restaurants, cafeterias, hotels, stadiums, and grocery stores post excess food in under a minute on the Food Donate website.</p>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">2</div>
                            <h3 class="step-title">Food is <b style="color: #3c973f">SECURED</b></h3>
                            <p class="step-description">Registered charities immediately get notified about food donations and can claim any donations they can use to serve the needy peoples.</p>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">3</div>
                            <h3 class="step-title">Food is <b style="color: #3c973f">PICKED UP</b></h3>
                            <p class="step-description">The charities picks up the food from the provided locaion by the donor and serves it to the needy peoples.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Food Facts Section -->
            <div class="food-facts-section section">
                <!-- <p class="sub-heading">Understanding the problem.</p> -->
                <h2 class="heading">What type of excess food we donate?</h2>
                <div class="cards-container">
                    <div class="card">
                        <div class="card__image">
                            <div class="fact-icon">🍎</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title">RAW FOOD WASTE</span>
                            <p class="card__text">40% of all food is thrown away or plowed over. In California, 100 billion pounds of raw food go to waste annually, representing a massive opportunity for redirection.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <div class="fact-icon">🍽️</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b>PREPARED</b> FOOD WASTE</span>
                            <p class="card__text">In California alone, over 5 billion pounds of prepared food is thrown away every year, while millions of people face food insecurity daily.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <div class="fact-icon">🤝</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b>HUNGER</b> SOLUTION</span>
                            <p class="card__text">If only one-third of California's excess food were diverted, it would resolve the hunger crisis and create a sustainable food ecosystem for all.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pickup Section -->
            <section class="pickup-section">
                <div class="section-content">
                    <div class="pickup-image-wrapper">
                        <img src="../img/delivery.gif" alt="Pickup" class="pickup-image">
                    </div>
                    <div class="pickup-details">
                        <h2 class="section-title">Door Pickup</h2>
                        <p class="text">Door pickup services play a vital role in food waste management by providing a convenient way for households and businesses to dispose of surplus or leftover food responsibly. Instead of ending up in landfills, collected food can be redirected for donation to those in need. This promotes a culture of sustainability and conscious consumption. By making food waste disposal easier and more organized, door pickup systems help build cleaner, greener communities.</p>
                    </div>
                </div>
            </section>
            <!-- Testimonials Section -->
            <div class="testimonials-section section">
                <p class="sub-heading">Voices from our community.</p>
                <h2 class="heading">What Our Users Are Saying</h2>
                <div class="cards-container">
                    <div class="card">
                        <div class="card__image">
                            <div class="quote-icon">"</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b>RESTAURANT</b> PARTNERSHIP</span>
                            <p class="card__text">Plumed Horse is thrilled to partner with Waste No Food. For the distribution of our artisanal & locally sourced surplus products.</p>
                            <div class="author-info">
                                <div class="author-name">Josh Weeks</div>
                                <div class="author-title">General Manager, Plumed Horse</div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <div class="quote-icon">"</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b>PIONEERING</b> SUPPORT</span>
                            <p class="card__text">Dio Deka is pleased to be the first donor to Waste No Food's worthy cause. We are also happy to see it being adopted by many other restaurants.</p>
                            <div class="author-info">
                                <div class="author-name">Sakis Platis</div>
                                <div class="author-title">Partner at Dio Deka</div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <div class="quote-icon">"</div>
                        </div>
                        <div class="card__content">
                            <span class="card__title"><b>AWARD-WINNING</b> ENDORSEMENT</span>
                            <p class="card__text">Manresa is happy to be associated with Waste No Food and its mission. We are glad to help the program continue to grow.</p>
                            <div class="author-info">
                                <div class="author-name">David Kinch</div>
                                <div class="author-title">James Beard award winning chef owner of Manresa</div>
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
                    <div class="copyright">
                        <p> © Copyright 2025 Food Donate. All rights reserved.</p>
                    </div>
                </div>
            </footer>
            <!-- Back to Top Scrollbar -->
            <button class="scroll-to-top" id="scrollToTop">
                <i class="fas fa-chevron-up"></i>
            </button>
        </main>
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