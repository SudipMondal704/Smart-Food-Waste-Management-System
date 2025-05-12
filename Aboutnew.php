<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="About.css">
</head>
<body>

    <!-- ===== Header/Navbar Section ===== -->
    <header>
        <div class="logo">Food <b style="color: #06abc1;">Donate</b></div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="#about" class="active">About</a></li>
                <li><a href="Contact.php">Contact</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <!-- ===== JavaScript for Mobile Menu Toggle ===== -->
    <script>
        const hamburger = document.querySelector(".hamburger");
        hamburger.onclick = function () {
            const navBar = document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        };
    </script>

    <!-- ===== About Us Section ===== -->
    <section class="about-section" id="about">
        <div class="container">

            <!-- Intro Section -->
            <div class="intro">
                <div class="intro-text">
                    <h1><strong>About Us</strong></h1>
                    <p>We are a dedicated team working towards eradicating hunger and reducing food waste across India through technology and community support,we connect food donors with NGOs to help feed those in need.

</p>
                </div>
                <div class="intro-image">
                    <img src="img/Aboutus.png" alt="about">
                </div>
            </div>

            <!-- Mission Section -->
            <div class="mission-story">
                <div class="mission-image">
                    <img src="img/OurMission.png" alt="mission">
                </div>
                <div class="mission-text">
                    <h2>Our Mission</h2>
                    <p>Our mission is to create a smart and efficient platform that bridges the gap between food donors and the needy. We aim to make food donation quick, accessible, and impactful—ensuring that surplus food reaches the hungry instead of going to waste.

</p>
                </div>
            </div>

            <!-- Story Section -->
            <div class="story">
                <div class="story-text">
                    <h2>Our Services</h2>
                    <p>	Connect donors (individuals, families, event organizers, restaurants, etc.) with nearby NGOs.<br>	Allow donors to donate excess cooked, raw, or packaged food from events, ceremonies, and daily life.<br>	Enable NGOs to receive donation requests and collect food directly from the donor's location.<br>	Ensure food is safely distributed to poor, needy, and street-hungry individuals.<br></p>
                </div>
                <div class="story-image">
                    <img src="img/services.png" alt="Our service">
                </div>
            </div>
             
            <!-- Mission Section -->
            <div class="mission-story">
                <div class="mission-image">
                    <img src="img/goal.png" alt="goal">
                </div>
                <div class="mission-text">
                    <h2>Our Goal</h2>
                    <p>Every day, tons of edible food is wasted while millions go hungry. Our platform was built to solve both these problems—fighting hunger and reducing waste—with one simple solution.</p>
                </div>
            </div>
<!-- Intro Section -->
            <div class="intro">
                <div class="intro-text">
                    <h1>How It Works</h1>
                    <p>	Donors sign up and send a food donation request.
	Our system notifies the nearest NGO based on location.<br>
	The NGO picks up the food from the donor’s location.<br>
	Food is distributed to people in need quickly and safely.<br>
</p>
                </div>
                <div class="intro-image">
                    <img src="img/mobile.jpg" alt="how work">
                </div>
            </div>

            <!-- Mission Section -->
            <div class="mission-story">
                <div class="mission-image">
                    <img src="img/vision.png" alt="vision">
                </div>
                <div class="mission-text">
                    <h2>Our Vision</h2>
                    <p>We envision an India where hunger is no longer a daily struggle and every meal counts. Our goal is to ensure that no edible food is thrown away while millions go without a meal. By building a strong network of donors and NGOs, we strive to create a system where excess food is effectively redirected to those who need it most. We dream of a future where no food is wasted and no one sleeps hungry.

</p>
                </div>
            </div>

            <!-- Story Section -->
            <div class="story">
                <div class="story-text">
                    <h2>Get Involved</h2>
                    <p>Whether you are an individual, a restaurant, or an NGO, you can play a vital role in our mission. Donors can share their excess food, and NGOs can help deliver it to those who need it the most. Your small act of kindness can make a big difference in someone's life. Join us today—donate food, reduce waste, and help fight hunger.</p>
                </div>
                <div class="story-image">
                    <img src="img/involved.png" alt="involved">
                </div>
            </div>
           <!-- Numbers Section -->
            <div class="by-numbers">
                <h2>Our Contributors</h2>
                <div class="stats">
                    <div class="stat-box">
                        <h3>50+ Cities</h3>
                        <p>Across India</p>
                    </div>
                    <div class="stat-box">
                        <h3>1,000+ Donors</h3>
                        <p>Helping Hands</p>
                    </div>
                    <div class="stat-box">
                        <h3>500+ NGOs</h3>
                        <p>Partnered and Growing</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</body>
</html>
