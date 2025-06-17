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
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>    <style>
        .review-section {
            padding: 50px 20px;
            align-items: center;
            background: whitesmoke;
        }

        .review-label {
            color: #2dd4bf;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .review-title {
            color: #2d2d2d;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .review-underline {
            width: 80px;
            height: 4px;
            background: #2dd4bf;
            margin: 0 auto;
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
                            <a href="service.php">Service</a>
                            <a href="#">Donate</a>
                            <a href="team.php">Our Team</a>
                            <a href="voices-of-community.php" class="active">Voices of Community</a>
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
                                <span id="profile-type"> A/c Type : <?php echo htmlspecialchars($_SESSION['user_type']); ?></span>
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
                    <h2 class="title">Voices of Community</h2>
                    <p class="description">
                        Home > Pages > <a href="#" class="active">Voices of Community</a>
                    </p>
                </div>
            </section>
        </div>
        
        <!-- Review Testimonials Section -->
            <div class="review-section">
                <div class="review-testimonial-container">
                    <div class="review-testimonial-header">
                        <div class="review-label">Voices of our community !!</div>
                        <h2 class="review-title">What are our Users saying ?</h2>
                        <div class="review-underline"></div>
                    </div>

                    <div class="review-testimonials-wrapper">
                        <button class="review-nav-arrow" id="reviewPrevBtn">‹</button>
                        
                        <div class="review-testimonials-slider">
                            <div class="review-testimonials-track" id="reviewTestimonialsTrack">
                                <!-- Slide 1 -->
                                <div class="review-testimonial-slide">
                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★★</div>
                                        <p class="review-testimonial-text">This food donation platform has transformed how we help our community. The process is seamless and we can see exactly where our donations go. It's wonderful to know we're making a real difference.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial">S</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">SAMYUKTA PAL</div>
                                                <div class="review-customer-role">Community Volunteer</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★★</div>
                                        <p class="review-testimonial-text">As a restaurant owner, this platform makes it so easy to donate our excess food instead of wasting it. The pickup service is reliable and we love contributing to feeding families in need.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial">A</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">ASIF KHAN</div>
                                                <div class="review-customer-role">Restaurant Owner</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★☆</div>
                                        <p class="review-testimonial-text">The transparency and efficiency of this donation system is remarkable. We can track our contributions and see the impact we're making in fighting hunger in our local community.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial">L</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">LIPIKA DAWN</div>
                                                <div class="review-customer-role">NGO Coordinator</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide 2 -->
                                <div class="review-testimonial-slide">
                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★★</div>
                                        <p class="review-testimonial-text">This platform connected us with so many generous donors. We've been able to distribute fresh meals to hundreds of families thanks to the efficient donation system and caring community.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial" >M</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">MANOJ KUMAR</div>
                                                <div class="review-customer-role">Food Bank Director</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★★</div>
                                        <p class="review-testimonial-text">As a corporate donor, we appreciate how simple it is to coordinate large-scale food donations. The platform handles logistics perfectly and ensures nothing goes to waste.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial" >M</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">MUKESH DAS</div>
                                                <div class="review-customer-role">CSR Manager</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-testimonial-card">
                                        <div class="review-quote-icon">"</div>
                                        <div class="review-star-rating">★★★★☆</div>
                                        <p class="review-testimonial-text">The real-time tracking and impact reports help us understand exactly how our donations are helping the community. It's inspiring to see the difference we're making together.</p>
                                        <div class="review-customer-info">
                                            <div class="review-customer-avatar">
                                                <span class="review-initial" >R</span>
                                            </div>
                                            <div class="review-customer-details">
                                                <div class="review-customer-name">RAMU SHAW</div>
                                                <div class="review-customer-role">Charity Organizer</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button class="review-nav-arrow" id="reviewNextBtn">›</button>
                    </div>

                    <div class="review-navigation">
                        <div class="review-dots-container">
                            <div class="review-dot active" data-slide="0"></div>
                            <div class="review-dot" data-slide="1"></div>
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
  <script src="../js/script.js"></script>
</body>
</html>