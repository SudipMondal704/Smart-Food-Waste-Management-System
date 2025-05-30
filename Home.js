// Image Slider functionality
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

// Login Modal functionality
const modalContainer = document.getElementById('loginModalContainer');
const loginBtn = document.getElementById('loginBtn');
const donateBtn = document.getElementById('donateBtn');

// Event listeners for opening modal
loginBtn.addEventListener('click', function(e) {
    e.preventDefault();
    loadLoginModal();
});

donateBtn.addEventListener('click', function(e) {
    e.preventDefault();
    loadLoginModal();
});

function loadLoginModal() {
    // Show loading state
    modalContainer.innerHTML = `
        <div class="modal-content">
            <div class="close-btn">&times;</div>
            <div class="login-container">
                <div style="display: flex; justify-content: center; align-items: center; height: 200px; font-size: 18px; color: #512da8;">
                    Loading...
                </div>
            </div>
        </div>
    `;
    
    // Show modal
    modalContainer.classList.add('show');
    
    // Add temporary close functionality for loading state
    const tempCloseBtn = modalContainer.querySelector('.close-btn');
    tempCloseBtn.addEventListener('click', function() {
        modalContainer.classList.remove('show');
    });
    
    // Load content from login_signup.php
    fetch('login_signup.php')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const modalContent = doc.querySelector('.modal-content');
            
            // Clear the container and add the loaded content
            modalContainer.innerHTML = '';
            modalContainer.appendChild(modalContent);
            setupModalEventListeners();
        });
}

function setupModalEventListeners() {
    const closeBtn = modalContainer.querySelector('.close-btn');
    const container = modalContainer.querySelector('.login-container');
    const registerBtn = modalContainer.querySelector('#register');
    const loginBackBtn = modalContainer.querySelector('#login');
    
    // Close button functionality
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modalContainer.classList.remove('show');
            if (container) container.classList.remove("active");
        });
    }
    
    // Register button functionality (switch to sign up)
    if (registerBtn && container) {
        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });
    }
    
    // Login button functionality (switch to sign in)
    if (loginBackBtn && container) {
        loginBackBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    }
    
    // Close modal when clicking outside
    modalContainer.addEventListener('click', function(event) {
        if (event.target === modalContainer) {
            modalContainer.classList.remove('show');
            if (container) container.classList.remove("active");
        }
    });
    
    // Handle form submissions if forms exist
    const signInForm = modalContainer.querySelector('.sign-in form');
    const signUpForm = modalContainer.querySelector('.sign-up form');
    
    if (signInForm) {
        signInForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleSignIn(this);
        });
    }
    
    if (signUpForm) {
        signUpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleSignUp(this);
        });
    }
}

function handleSignIn(form) {
    const formData = new FormData(form);
    
    // Send data to server
    fetch('login_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle successful login
            modalContainer.classList.remove('show');
            window.location.reload();
        }
    });
}

function handleSignUp(form) {
    const formData = new FormData(form);
    
    // Send data to server
    fetch('register_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle successful registration
            alert('Registration successful! Please log in.');
            // Switch to login form
            const container = modalContainer.querySelector('.login-container');
            if (container) container.classList.remove("active");
        }
    });
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modalContainer.classList.contains('show')) {
        modalContainer.classList.remove('show');
        const container = modalContainer.querySelector('.login-container');
        if (container) container.classList.remove("active");
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Food Donate page loaded successfully');
    checkUserSession();
});

function checkUserSession() {
    // Check if user is already logged in
    fetch('check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // Update UI for logged-in user
                const loginBtn = document.getElementById('loginBtn');
                if (loginBtn) {
                    loginBtn.textContent = 'Logout';
                    loginBtn.href = 'logout.php';
                }
            }
        });
}