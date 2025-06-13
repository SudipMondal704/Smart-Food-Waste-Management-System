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
    <title>easyDonate - Save Food Share joy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="../css/style.css" rel="stylesheet">
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
                    <a href="About.php" style="--i:2">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" style="--i:3">Pages <i class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Service</a>
                        <a href="#">Donate</a>
                        <a href="team.php">Our Team</a>
                        <a href="#">Voices of Community</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="Contact.php" style="--i:4">Contact</a>
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
                        <!-- Content Section 1 -->
                        <div class="content-section">
                            <h1 class="title">
                                Let's Make The Change
                                <span>"Save Food, Share Joy!!"</span>
                            </h1>
                            <p class="description">
                                Join our mission to end food waste and hunger. Every meal you donate makes a difference in someone's life. Together, we can create a world where surplus food reaches those who need it most.
                            </p>
                        </div>
                        
                        <!-- Content Section 2 -->
                        <div class="content-section">
                            <h1 class="title">
                                Transform Lives Through Food
                                <span>"Every Donation Counts!!"</span>
                            </h1>
                            <p class="description">
                                Transform your extra food into hope for families in need. Our platform connects generous donors with local communities, ensuring fresh meals reach hungry hearts across the city.
                            </p>
                        </div>
                        
                        <!-- Content Section 3 -->
                        <div class="content-section">
                            <h1 class="title">
                                Building Sustainable Communities
                                <span>"Be The Change Today!!"</span>
                            </h1>
                            <p class="description">
                                Be the change you want to see. From restaurants to home kitchens, every contribution counts. Help us build a sustainable future where no good food goes to waste and no one sleeps hungry.
                            </p>
                        </div>
                        
                        <!-- Content Section 4 -->
                        <div class="content-section">
                            <h1 class="title">
                                Nourish Souls, Strengthen Hearts
                                <span>"Start Your Journey Now!!"</span>
                            </h1>
                            <p class="description">
                                Your kindness feeds more than just bodies - it nourishes souls and strengthens communities. Start your donation journey today and become part of a movement that's changing lives, one meal at a time.
                            </p>
                        </div>
                    </div>
                    <div class="button">
                        <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/fooddetails.php" class="btn" id="donateNowBtn">Donate Food<i class="fa-solid fa-circle-arrow-right"></i>
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
                <div class="ambition-header">
                    <p class="ambition-label">Look what we can do together.</p>
                    <h2 class="ambition-title">Our Ambitions</h2>
                    <div class="ambition-underline"></div>
                </div>
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
                    <h2 class="how-it-works-title">How does easyDonate works?</h2>
                    
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
                            <img src="../img/Raw-food.jpg">
                        </div>
                        <div class="card__content">
                            <span class="card__title">RAW FOOD</span>
                            <p class="card__text">In India, a significant portion of fruits and vegetables is wasted at the farm level due to poor infrastructure and supply chain issues. Donating raw food helps reduce this loss and nourishes many in need.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <img src="../img/Cooke-food.jpg">
                        </div>
                        <div class="card__content">
                            <span class="card__title">PREPARED / COOKED FOOD </span>
                            <p class="card__text">Every day, thousands of kilograms of cooked food go to waste in Indian households, restaurants, and events. This surplus food can instead be redirected to feed the millions who go to bed hungry.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__image">
                            <img src="../img/Packaged-food.jpg">
                        </div>
                        <div class="card__content">
                            <span class="card__title">PACKAGED FOOD</span>
                            <p class="card__text">Excess packaged food nearing expiry is often discarded by retailers and consumers. By collecting and donating it safely, we can support food banks and reduce India's mounting food waste problem.</p>
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
            <!-- Review Testimonials Section -->
            <div class="review-testimonials-section">
                <div class="review-testimonial-container">
                    <div class="review-testimonial-header">
                        <div class="review-testimonial-label">Voices of our community !!</div>
                        <h2 class="review-testimonial-title">What are our Users saying ?</h2>
                        <div class="review-title-underline"></div>
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
                                                <span class="review-initial" style="display: none;">A</span>
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
                                                <span class="review-initial" style="display: none;">M</span>
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
                                                <span class="review-initial" style="display: none;">M</span>
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
                                                <span class="review-initial" style="display: none;">R</span>
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
                                <p>Dewandighi, Katwa Road, Purba Bardhaman , 713102</p>
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