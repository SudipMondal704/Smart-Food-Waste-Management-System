<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <title>Food Donate - Home Page</title>

  <link rel="stylesheet" href="Home.css">

</head>

<body>

  <header class="header">
    <a href="#" class="logo"><span style="color: #222;">Food</span> <b style="color: rgb(238, 120, 41);">Donate</b></a>
    
    <!-- Hamburger Menu Icon for Mobile -->
    <div class="menu-icon">
      <div></div>
      <div></div>
      <div></div>
    </div>
  
    <nav class="navbar">
      <a href="Home.html" style="--i:1" class="active">Home</a>
      <a href="About.html" style="--i:2">About</a>
      <a href="Contact.html" style="--i:3">Contact</a>
      <a href="login_signup.php" style="--i:4" id="loginBtn">Login</a>
    </nav>
  </header>

  <!-- Login/Signup Modal -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <!-- Login Container -->
      <div class="login-container" id="login-container">
        <div class="form-container sign-up">
            <h1>Create Account</h1>
             <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Name"required>

            <div class="account-type">
              <label><b>Account Type :</b></label><br>
              <label><input type="radio" name="acc-type" value="Donar"required /> Donor</label>
              <label><input type="radio" name="acc-type" value="NGO"required/> NGO</label>
            </div>
            <input type="text" name="address" placeholder="Address"required>
            <input type="email" name="email" placeholder="Email"required>
            <input type="tel" name="phone" placeholder="Mobile no."required>
            <input type="password" name="password" placeholder="Password"required>

            <div class="checkbox">
              <label><input type="checkbox" required ></label>
              <span>I agree to the <a href="">terms & conditions</a></span>
            </div>
            <button>Sign Up</button>
          </form>
        </div>
        <div class="form-container sign-in">
           <form action="signin.php" method="POST">
             <h1>Sign In</h1>
           <input type="email" name="login" placeholder="Email or Mobile Number">
            <input type="password"name="password" placeholder="Password">
            <a href="forgot_password.php">Forget Your Password?</a>
            <button>Sign In</button>
          </form>
        </div>
        <div class="toggle-container">
          <div class="toggle">
            <div class="toggle-panel toggle-left">
              <h1>Welcome Back!</h1>
              <p>Enter your personal details to use all of site features</p>
              <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
              <h1>Hello, Friend!</h1>
              <p>Register with your personal details to use all of site features</p>
              <button class="hidden" id="register">Sign Up</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <section class="home">
    <div class="home-content">
      <h1>Save Food, Share Joy !!</h1>
      <p>Cutting food waste is a delicious way of saving money, helping to feed the world and protect the planet. So donate extra food to needy people because giving is not charity, it is an expression of Humanity.</p>
      <a href="#" class="btn" id="donateBtn">Donate Now</a>
    </div>

    <div class="slider">
        <div class="list">
            <div class="item">
                <img src="img/sliding-image1.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/sliding-image2.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/sliding-image3.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/sliding-image4.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/sliding-image5.jpg" alt="">
            </div>
        </div>
        <div class="buttons">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>
        <ul class="dots">
            <li class="active"></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
  </section>

  <div class="photo">
    <p class="heading">Our Works</p>
    <p class="sub-heading">Look what we can do together.</p>
       
    <div class="wrapper">
      <div class="box"><img src="img/p1.jpeg" alt=""></div>
      <div class="box"><img src="img/p4.jpeg" alt=""></div>
      <div class="box"><img src="img/p3.jpeg" alt=""></div>
    </div>
  </div>  
    
    
  <div class="photo">
    <p class="heading">DOOR PICKUP</p>
    <p  class="sub-heading">"Your donate will be immediately collected and sent to needy people "</p>
    <div class="deliver"><img src="img/delivery.gif" alt=""></div>
  </div>

  <footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <h2>About Us</h2>
            <p class="about">
                The basic concept of this project <strong>Food Waste Management</strong> 
                is to collect the excess / leftover food from donors such as hotels, restaurants, 
                marriage halls, etc. and distribute to the needy people.
            </p>
            <p>Join us in our mission to reduce food waste and help those in need.</p>
        </div>

        <div class="footer-center">
            <h2>Contact Us</h2>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Dewandighi, Katwa Road, Purba Bardhaman, 713102</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <p>(+00) 0000 000 000</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <p><a href="mailto:fooddonate@gmail.com">fooddonate@gmail.com</a></p>
            </div>
            <ul class="social">
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
            </ul>
        </div>

        <div class="footer-right">
            <h2>Food<span>Donate</span></h2>
            <p>Help us make a difference one meal at a time.</p>
            <p class="menu">
                <a href="Home.html">Home</a> |
                <a href="About.html">About</a> |
                <a href="#">Contact</a> |
            </p>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2025 FoodDonate. All rights reserved.</p>
    </div>
</footer>

<script>
// Slider functionality
let slider = document.querySelector('.slider .list');
let items = document.querySelectorAll('.slider .list .item');
let next = document.getElementById('next');
let prev = document.getElementById('prev');
let dots = document.querySelectorAll('.slider .dots li');

let lengthItems = items.length - 1;
let active = 0;
next.onclick = function(){
    active = active + 1 <= lengthItems ? active + 1 : 0;
    reloadSlider();
}
prev.onclick = function(){
    active = active - 1 >= 0 ? active - 1 : lengthItems;
    reloadSlider();
}
let refreshInterval = setInterval(()=> {next.click()}, 3000);
function reloadSlider(){
    slider.style.left = -items[active].offsetLeft + 'px';
    // 
    let last_active_dot = document.querySelector('.slider .dots li.active');
    last_active_dot.classList.remove('active');
    dots[active].classList.add('active');

    clearInterval(refreshInterval);
    refreshInterval = setInterval(()=> {next.click()}, 3000);
}

dots.forEach((li, key) => {
    li.addEventListener('click', ()=>{
         active = key;
         reloadSlider();
    })
})
window.onresize = function(event) {
    reloadSlider();
};

// Login Modal Functionality
const modal = document.getElementById('loginModal');
const loginBtn = document.getElementById('loginBtn');
const donateBtn = document.getElementById('donateBtn');
const closeBtn = document.querySelector('.close-btn');
const registerBtn = document.getElementById('register');
const loginBackBtn = document.getElementById('login');
const container = document.getElementById('login-container');

// Show the modal when login button is clicked
loginBtn.addEventListener('click', function() {
    modal.style.display = 'flex';
});

donateBtn.addEventListener('click', function() {
    modal.style.display = 'flex';
});

// Close the modal when X is clicked
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    // Reset to sign-in view when closing
    container.classList.remove("active");
});

// Close the modal if clicked outside
window.addEventListener('click', function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        // Reset to sign-in view when closing
        container.classList.remove("active");
    }
});

// Toggle between sign-in and sign-up
registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBackBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Mobile Menu Toggle
const menuIcon = document.querySelector('.menu-icon');
const navbar = document.querySelector('.navbar');

menuIcon.addEventListener('click', () => {
    navbar.classList.toggle('active');
    // Animate hamburger to X
    const bars = menuIcon.querySelectorAll('div');
    bars[0].classList.toggle('bar1-active');
    bars[1].classList.toggle('bar2-active');
    bars[2].classList.toggle('bar3-active');
});

// Close mobile menu when a menu item is clicked
document.querySelectorAll('.navbar a').forEach(item => {
    item.addEventListener('click', () => {
        navbar.classList.remove('active');
        // Reset hamburger animation
        const bars = menuIcon.querySelectorAll('div');
        bars[0].classList.remove('bar1-active');
        bars[1].classList.remove('bar2-active');
        bars[2].classList.remove('bar3-active');
    });
});

// Fix slider for mobile
function updateSliderForMobile() {
    if (window.innerWidth <= 768) {
        // Fix for mobile slider - ensure each slide takes full width
        const sliderItems = document.querySelectorAll('.slider .list .item');
        sliderItems.forEach(item => {
            item.style.width = window.innerWidth + 'px';
        });
    }
}

// Run on page load and resize
updateSliderForMobile();
window.addEventListener('resize', updateSliderForMobile);

// Ensure toggle buttons work properly on small screens
function handleLoginToggle() {
    if (window.innerWidth <= 768) {
        // For mobile, we need to make sure both forms are accessible
        const signIn = document.querySelector('.sign-in');
        const signUp = document.querySelector('.sign-up');
        
        registerBtn.addEventListener('click', () => {
            signIn.style.display = 'none';
            signUp.style.display = 'block';
        });
        
        loginBackBtn.addEventListener('click', () => {
            signIn.style.display = 'block';
            signUp.style.display = 'none';
        });
    }
}

// Check screen size on load
window.addEventListener('load', handleLoginToggle);
</script>

</body>
</html>