const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");

// Input field focus/blur effects
inputs.forEach((inp) => {
  inp.addEventListener("focus", () => {
    inp.classList.add("active");
  });
  inp.addEventListener("blur", () => {
    if (inp.value != "") return;
    inp.classList.remove("active");
  });
});

// Toggle between sign-in and sign-up forms
toggle_btn.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    main.classList.toggle("sign-up-mode");
  });
});

// Image carousel functionality
function moveSlider() {
  let index = this.dataset.value;

  let currentImage = document.querySelector(`.img-${index}`);
  images.forEach((img) => img.classList.remove("show"));
  currentImage.classList.add("show");

  const textSlider = document.querySelector(".text-group");
  textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

  bullets.forEach((bull) => bull.classList.remove("active"));
  this.classList.add("active");
}

bullets.forEach((bullet) => {
  bullet.addEventListener("click", moveSlider);
});

// Auto-slide carousel every 4 seconds
let currentSlide = 1;
const totalSlides = 3;

function autoSlide() {
  currentSlide = currentSlide >= totalSlides ? 1 : currentSlide + 1;
  
  // Find the bullet with the current slide value and trigger click
  const currentBullet = document.querySelector(`[data-value="${currentSlide}"]`);
  if (currentBullet) {
    moveSlider.call(currentBullet);
  }
}

// Start auto-slide
setInterval(autoSlide, 4000);

// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
  // Handle radio button styling
  const radioButtons = document.querySelectorAll('input[type="radio"]');
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function() {
      // Remove active class from all radio labels in the same group
      const groupName = this.name;
      const sameGroupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
      sameGroupRadios.forEach(r => {
        r.closest('.radio-label').classList.remove('active');
      });
      // Add active class to selected radio label
      this.closest('.radio-label').classList.add('active');
    });
  });

  // Handle checkbox styling
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      this.closest('.checkbox-label').classList.toggle('active', this.checked);
    });
  });

  // Form submission handling
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      // Basic validation
      const requiredFields = this.querySelectorAll('[required]');
      let isValid = true;
      
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('error');
          // Remove error class after 3 seconds
          setTimeout(() => field.classList.remove('error'), 3000);
        } else {
          field.classList.remove('error');
        }
      });

      // Email validation
      const emailFields = this.querySelectorAll('input[type="email"]');
      emailFields.forEach(email => {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value && !emailPattern.test(email.value)) {
          isValid = false;
          email.classList.add('error');
          setTimeout(() => email.classList.remove('error'), 3000);
        }
      });

      // Phone validation
      const phoneFields = this.querySelectorAll('input[type="tel"]');
      phoneFields.forEach(phone => {
        const phonePattern = /^[0-9]{10}$/;
        if (phone.value && !phonePattern.test(phone.value.replace(/\D/g, ''))) {
          isValid = false;
          phone.classList.add('error');
          setTimeout(() => phone.classList.remove('error'), 3000);
        }
      });

      if (!isValid) {
        e.preventDefault();
        // Show error message
        showMessage('Please fill all required fields correctly', 'error');
      }
    });
  });
});

// Utility function to show messages
function showMessage(message, type = 'info') {
  // Remove existing messages
  const existingMessage = document.querySelector('.form-message');
  if (existingMessage) {
    existingMessage.remove();
  }

  // Create new message element
  const messageDiv = document.createElement('div');
  messageDiv.className = `form-message ${type}`;
  messageDiv.textContent = message;
  
  // Insert message at the top of the main container
  const mainContainer = document.querySelector('main');
  mainContainer.insertBefore(messageDiv, mainContainer.firstChild);

  // Remove message after 5 seconds
  setTimeout(() => {
    if (messageDiv.parentNode) {
      messageDiv.remove();
    }
  }, 5000);
}

// Handle window resize for better responsive behavior
window.addEventListener('resize', function() {
  // Reset carousel if window is resized
  if (window.innerWidth <= 850) {
    // Hide carousel images on mobile
    const carousel = document.querySelector('.carousel');
    if (carousel) {
      carousel.style.minHeight = 'auto';
    }
  }
});

// Initialize input states on page load
document.addEventListener('DOMContentLoaded', function() {
  inputs.forEach(input => {
    if (input.value.trim() !== '') {
      input.classList.add('active');
    }
  });
});