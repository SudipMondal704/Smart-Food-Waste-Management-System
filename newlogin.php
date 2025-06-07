<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

      *,
      *::before,
      *::after {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
      }

      /* Hide scrollbars completely */
      body {
        overflow: hidden;
      }
      
      /* Hide scrollbars for webkit browsers */
      ::-webkit-scrollbar {
        display: none;
      }
      
      /* Hide scrollbars for Firefox */
      html {
        scrollbar-width: none;
      }

      body,
      input {
        font-family: "Poppins", sans-serif;
      }

      main {
        width: 100%;
        height: 100vh;
        background-color: #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
      }

      .box {
        position: relative;
        width: 100%;
        max-width: 900px;
        height: 560px;
        background-color: #fffdfd;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        border: 1px solid #ddd;
        overflow: hidden;
      }

      .inner-box {
        position: relative;
        width: 100%;
        height: 100%;
      }

      .forms-wrap {
        position: absolute;
        height: 100%;
        width: 45%;
        top: 0;
        left: 0;
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr;
        transition: 0.8s ease-in-out;
        overflow: hidden;
      }

      form {
        max-width: 300px;
        width: 100%;
        margin: 0 auto;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        grid-column: 1 / 2;
        grid-row: 1 / 2;
        transition: opacity 0.02s 0.4s;
        padding: 20px 0;
        overflow-y: auto;
        overflow-x: hidden;
      }

      

      form.sign-up-form {
        opacity: 0;
        pointer-events: none;
      }

      .logo {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
      }

      .logo img {
        width: 35px;
        margin-right: 0.3rem;
      }

      .logo h4 {
        font-size: 1.1rem;
        letter-spacing: -0.2px;
        color: #151111;
      }

      .heading {
        margin-bottom: 15px;
      }

      .heading h2 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #151111;
      }

      .actual-form {
        flex: 1;
        display: flex;
        flex-direction: column;
      }

      .heading h6 {
        color: #bababa;
        font-weight: 400;
        font-size: 0.75rem;
        display: inline;
      }

      .toggle {
        color: #512da8;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: 0.3s;
      }

      .toggle:hover {
        color: #8371fd;
      }

      .input-wrap {
        position: relative;
        height: 25px;
        margin-bottom: 18px;
      }

      .input-field {
        position: absolute;
        width: 100%;
        height: 100%;
        background: none;
        border: none;
        outline: none;
        border-bottom: 1px solid #bbb;
        padding: 0;
        font-size: 0.95rem;
        color: #151111;
        transition: 0.4s;
      }

      .input-field.error {
        border-bottom-color: #ff4444;
      }

      label {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.85rem;
        color: #bbb;
        pointer-events: none;
        transition: 0.4s;
      }

      .input-field.active {
        border-bottom-color: #512da8;
      }

      .input-field.active + label {
        font-size: 0.75rem;
        top: -2px;
        color: #512da8;
      }

      /* Account Type Styles */
      .account-type {
        margin-bottom: 10px;
      }

      .account-type .type-label {
        display: block;
        font-size: 0.85rem;
        color: #151111;
        margin-bottom: 8px;
        position: static;
        transform: none;
        font-weight: 600;
      }

      .radio-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        cursor: pointer;
      }

      .radio-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #151111;
        cursor: pointer;
        position: static;
        transform: none;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
      }

      .radio-label input[type="radio"] {
        width: 12px;
        height: 12px;
        position: relative;
        cursor: pointer;
        accent-color: #512da8;
        z-index: 10;
        pointer-events: auto;
      }

      .radio-label span {
        font-size: 0.8rem;
        user-select: none;
      }

      .radio-label:hover {
        background-color: #f5f5f5;
      }

      .radio-label.active {
        color: #512da8;
        font-weight: 500;
        background-color: rgba(81, 45, 168, 0.1);
      }

      /* Checkbox Styles */
      .checkbox-wrap {
        margin-bottom: 1rem;
      }

      .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.8rem;
        color: #151111;
        cursor: pointer;
        position:relative;
        transform: none;
        line-height: 1.2;
        border-radius: 4px;
        transition: all 0.3s ease;
      }

      .checkbox-label input[type="checkbox"] {
        width: 12px;
        height: 12px;
        margin-top: 2px;
        cursor: pointer;
        accent-color: #512da8;
        position: relative;
        z-index: 10;
        pointer-events: auto;
        flex-shrink: 0;
      }

      .checkbox-label:hover {
        background-color: #f5f5f5;
      }

      .checkbox-text {
        font-size: 0.8rem;
        user-select: none;
      }

      .checkbox-text a {
        color: #512da8;
        text-decoration: none;
        transition: 0.3s;
      }

      .checkbox-text a:hover {
        color: #8371fd;
        text-decoration: underline;
      }

      .checkbox-label.active {
        color: #512da8;
        background-color: rgba(81, 45, 168, 0.1);
      }

      .sign-btn {
        display: inline-block;
        width: 100%;
        height: 43px;
        background-color: #512da8;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 0.8rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        transition: 0.3s;
        
      }

      .sign-btn:hover {
        background-color: #8371fd;
      }

      .text {
        color: #bbb;
        font-size: 13px;
        text-align: center;
      }

      .text a {
        color: #512da8;
        text-decoration: none;
        transition: 0.3s;
      }

      .text a:hover {
        color: #8371fd;
      }

      main.sign-up-mode form.sign-in-form {
        opacity: 0;
        pointer-events: none;
      }

      main.sign-up-mode form.sign-up-form {
        opacity: 1;
        pointer-events: all;
      }

      main.sign-up-mode .forms-wrap {
        left: 55%;
      }

      main.sign-up-mode .carousel {
        left: 0%;
      }

      main.sign-up-mode .text-slider {
        left: 0%;
      }

      .carousel {
        position: absolute;
        height: 100%;
        width: 55%;
        left: 45%;
        top: 0;
        overflow: hidden;
        transition: 0.8s ease-in-out;
        padding: 1rem;
      }

      .images-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr;
        height: 100%;
        position: relative;
      }

      .image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        grid-column: 1/2;
        grid-row: 1/2;
        opacity: 0;
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
      }

      .img-1 {
        transform: translate(0, -20px);
      }

      .img-2 {
        transform: scale(0.95);
      }

      .img-3 {
        transform: scale(0.9) rotate(-5deg);
      }

      .image.show {
        opacity: 1;
        transform: none;
      }

      .text-slider {
        position: absolute;
        height: 100%;
        width: 55%;
        left: 45%;
        top: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 2rem;
        overflow: hidden;
        transition: 0.8s ease-in-out;
        pointer-events: none;
      }

      .text-wrap {
        height: 2.2rem;
        overflow: hidden;
        margin-bottom: 1rem;
        position: relative;
      }

      .text-group {
        display: flex;
        flex-direction: column;
        text-align: center;
        transform: translateY(0);
        transition: transform 0.5s ease-in-out;
        position: absolute;
        width: 100%;
      }

      .text-group h2 {
        line-height: 2.2rem;
        font-weight: 600;
        font-size: 1.4rem;
        color: #fff;
        height: 2.2rem;
      }

      .bullets {
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: all;
      }

      .bullets span {
        display: block;
        width: 0.5rem;
        height: 0.5rem;
        background-color: #a2e4a3;
        margin: 0 0.25rem;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.3s;
      }

      .bullets span.active {
        width: 1.1rem;
        background-color: #12c818;
        border-radius: 1rem;
      }

      /* Gradient overlay for better text visibility */
      .carousel::after {
        content: '';
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        right: 1rem;
        height: 40%;
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.8));
        pointer-events: none;
      }

      /* Form message styles */
      .form-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 0.9rem;
        font-weight: 500;
        z-index: 1000;
        max-width: 90%;
        text-align: center;
      }

      .form-message.error {
        background-color: #ff4444;
        color: white;
      }

      .form-message.success {
        background-color: #12c818;
        color: white;
      }

      .form-message.info {
        background-color: #512da8;
        color: white;
      }
    </style>
  </head>
  <body>
    <main>
      <div class="box">
        <div class="inner-box">
          <div class="forms-wrap">
            <form  action="signin.php" method="POST"  autocomplete="off" class="sign-in-form">
              <div class="logo">
                <img src="img/logo.png" alt="easyclass" />
                <h4>easy<b style="color: #34b409;">Donate</b></h4>
              </div>
              <div class="heading">
                <h2>Welcome Back</h2>
                <h6>Not registered yet?</h6>
                <a href="#" class="toggle">Sign up</a>
              </div>
              <div class="actual-form">
                <div class="input-wrap">
                  <input type="email" name="login" minlength="4" class="input-field" autocomplete="off" required/> 
                  <label>Email or Mobile Number</label>
                </div>
                <div class="input-wrap">
                  <input type="password" name="password" minlength="3" class="input-field" autocomplete="off" required/>  
                  <label>Password</label>
                </div>
                <input type="submit" value="Sign In" class="sign-btn" />
                <p class="text">
                  <a href="#">Forgotten your password?</a>
                </p>
              </div>
            </form>
            
            <form  action="signup.php" method="POST" autocomplete="off" class="sign-up-form"enctype="multipart/form-data">
              <div class="logo">
                <img src="img/logo.png" alt="easyclass" />
                <h4>easy<b style="color: #34b409;">Donate</b></h4>
              </div>
              <div class="heading">
                <h2>Create Account</h2>
                <h6>Already have an account?</h6>
                <a href="#" class="toggle">Sign in</a>
              </div>
              <div class="actual-form">
                
                
                <div class="account-type">
                  <label class="type-label"><strong>Select Account Type :</strong></label>
                  <div class="radio-group">
                    <label class="radio-label">
                      <input type="radio" name="acc-type" value="Donor" required /> 
                      <span>Donor</span>
                    </label>
                    <label class="radio-label">
                      <input type="radio" name="acc-type" value="NGO" required/> 
                      <span>NGO</span>
                    </label>
                  </div>
                </div>
                <div class="input-wrap">
                  <input type="text" name="username" minlength="4" class="input-field" autocomplete="off" required/> 
                  <label>Name</label>
                </div>
                <div class="input-wrap">
                  <input type="text" name="address" class="input-field" autocomplete="off" required/> 
                  <label>Address</label>
                </div>
                <div class="input-wrap">
                  <input type="email" name="email" class="input-field" autocomplete="off" required/> 
                  <label>Email</label>
                </div>
                <div class="input-wrap">
                  <input type="tel" name="phone" class="input-field" autocomplete="off" required/> 
                  <label>Mobile Number</label>
                </div>
                <div class="input-wrap">
                  <input type="password" name="password" minlength="6" class="input-field" autocomplete="off" required/> 
                  <label>Password</label>
                </div>
                <div class="input-wrap">
                  <input type="file" name="image" accept="image/*">
                </div>
                <div class="checkbox-wrap">
                  <label class="checkbox-label">
                    <input type="checkbox" required />
                    <span class="checkbox-text">I agree to the <a href="#">Terms & Conditions</a></span>
                  </label>
                </div>
                
                <input type="submit" value="Sign Up" class="sign-btn">
              </div>
            </form>
          </div>

          <div class="carousel">
            <div class="images-wrapper">
              <img src="https://via.placeholder.com/500x400/ff6b6b/white?text=Raw+Food" class="image img-1 show" alt="" />
              <img src="https://via.placeholder.com/500x400/4ecdc4/white?text=Packaged+Food" class="image img-2" alt="" />
              <img src="https://via.placeholder.com/500x400/45b7d1/white?text=Cooked+Food" class="image img-3" alt="" />
            </div>
          </div>
          
          <div class="text-slider">
            <div class="text-wrap">
              <div class="text-group">
                <h2>Help Feed the Hungry</h2>
                <h2>Make a Difference Today</h2>
                <h2>Join Our Community</h2>
              </div>
            </div>

            <div class="bullets">
              <span class="active" data-value="1"></span>
              <span data-value="2"></span>
              <span data-value="3"></span>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
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

        // Update images
        let currentImage = document.querySelector(`.img-${index}`);
        images.forEach((img) => img.classList.remove("show"));
        currentImage.classList.add("show");

        // Update text slider - synchronized movement
        const textSlider = document.querySelector(".text-group");
        textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

        // Update bullets
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
        
        // Find the bullet with the current slide value and trigger the moveSlider function
        const currentBullet = document.querySelector(`[data-value="${currentSlide}"]`);
        if (currentBullet) {
          // Manually call moveSlider with the correct context
          moveSlider.call(currentBullet);
        }
      }

      // Start auto-slide
      setInterval(autoSlide, 4000);

      // Form validation and enhancement
      document.addEventListener('DOMContentLoaded', function() {
        // Handle radio button styling and functionality
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        const radioLabels = document.querySelectorAll('.radio-label');
        
        // Add click event to radio labels
        radioLabels.forEach(label => {
          label.addEventListener('click', function(e) {
            // Prevent event bubbling
            e.stopPropagation();
            const radio = this.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change'));
            }
          });
        });
        
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

        // Handle checkbox styling and functionality
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const checkboxLabels = document.querySelectorAll('.checkbox-label');
        
        // Add click event to checkbox labels
        checkboxLabels.forEach(label => {
          label.addEventListener('click', function(e) {
            // Prevent double-toggle if clicking directly on checkbox
            if (e.target.type !== 'checkbox') {
              e.stopPropagation();
              const checkbox = this.querySelector('input[type="checkbox"]');
              if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
              }
            }
          });
        });
        
        checkboxes.forEach(checkbox => {
          checkbox.addEventListener('change', function() {
            this.closest('.checkbox-label').classList.toggle('active', this.checked);
          });
        });

        // Form submission handling
        const signupForm = document.getElementById('signup-form');
        const signinForm = document.getElementById('signin-form');

        if (signupForm) {
          signupForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Basic validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let errors = [];
            
            requiredFields.forEach(field => {
              if (field.type === 'radio') {
                // Check if any radio button in the group is selected
                const radioGroup = this.querySelectorAll(`input[name="${field.name}"]`);
                const isRadioSelected = Array.from(radioGroup).some(radio => radio.checked);
                if (!isRadioSelected) {
                  isValid = false;
                  errors.push('Please select an account type');
                }
              } else if (field.type === 'checkbox') {
                if (!field.checked) {
                  isValid = false;
                  errors.push('Please agree to the Terms & Conditions');
                }
              } else if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                errors.push(`${field.previousElementSibling.textContent} is required`);
                // Remove error class after 3 seconds
                setTimeout(() => field.classList.remove('error'), 3000);
              } else {
                field.classList.remove('error');
              }
            });

            // Email validation
            const emailField = this.querySelector('input[type="email"]');
            if (emailField && emailField.value) {
              const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (!emailPattern.test(emailField.value)) {
                isValid = false;
                emailField.classList.add('error');
                errors.push('Please enter a valid email address');
                setTimeout(() => emailField.classList.remove('error'), 3000);
              }
            }

            // Phone validation
            const phoneField = this.querySelector('input[type="tel"]');
            if (phoneField && phoneField.value) {
              const phonePattern = /^[0-9]{10}$/;
              const cleanPhone = phoneField.value.replace(/\D/g, '');
              if (!phonePattern.test(cleanPhone)) {
                isValid = false;
                phoneField.classList.add('error');
                errors.push('Please enter a valid 10-digit phone number');
                setTimeout(() => phoneField.classList.remove('error'), 3000);
              }
            }

            // Password length validation
            const passwordField = this.querySelector('input[type="password"]');
            if (passwordField && passwordField.value && passwordField.value.length < 6) {
              isValid = false;
              passwordField.classList.add('error');
              errors.push('Password must be at least 6 characters long');
              setTimeout(() => passwordField.classList.remove('error'), 3000);
            }

            if (!isValid) {
              // Show error message with first error
              showMessage(errors[0] || 'Please fill all required fields correctly', 'error');
            } else {
              // Form is valid - show success message
              showMessage('Sign up successful! (This is a demo)', 'success');
              
              // Reset form after successful submission
              setTimeout(() => {
                this.reset();
                // Reset radio and checkbox active states
                document.querySelectorAll('.radio-label.active, .checkbox-label.active').forEach(label => {
                  label.classList.remove('active');
                });
                // Reset input field states
                inputs.forEach(input => {
                  input.classList.remove('active');
                });
              }, 2000);
            }
          });
        }

        if (signinForm) {
          signinForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
              if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                setTimeout(() => field.classList.remove('error'), 3000);
              } else {
                field.classList.remove('error');
              }
            });

            if (!isValid) {
              showMessage('Please fill all required fields', 'error');
            } else {
              showMessage('Sign in successful! (This is a demo)', 'success');
            }
          });
        }

        // Initialize input states on page load
        inputs.forEach(input => {
          if (input.value.trim() !== '') {
            input.classList.add('active');
          }
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
        
        // Insert message at the top of the body
        document.body.appendChild(messageDiv);

        // Remove message after 5 seconds
        setTimeout(() => {
          if (messageDiv.parentNode) {
            messageDiv.remove();
          }
        }, 5000);
      }
    </script>
  </body>
</html>
