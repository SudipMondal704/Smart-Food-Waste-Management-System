<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login and Signup</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #eaeaea;
    }

    /* Modal Popup Styles */
    .modal {
      display: flex;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      position: relative;
      margin: auto;
      width: 100%;
      max-width: 768px;
      animation: modalopen 0.6s;
    }

    @keyframes modalopen {
      from {opacity: 0; transform: translateY(-30px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 24px;
      color: #333;
      background: #fff;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      z-index: 1100;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .close-btn:hover {
      background: #f1f1f1;
    }

    /* Login Container Styles */
    .login-container{
      background-color: #fff;
      border-radius: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
      position: relative;
      overflow: hidden;
      width: 768px;
      max-width: 100%;
      min-height: 480px;
    }

    .login-container p{
      font-size: 14px;
      line-height: 20px;
      letter-spacing: 0.3px;
      margin: 20px 0;
    }

    .login-container span{
      font-size: 12px;
    }

    .login-container a{
      color: #333;
      font-size: 13px;
      text-decoration: none;
      margin: 15px 0 10px;
    }

    .login-container button{
      background-color: #512da8;
      color: #fff;
      font-size: 12px;
      padding: 10px 45px;
      border: 1px solid transparent;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-top: 10px;
      cursor: pointer;
    }

    .login-container button.hidden{
      background-color: transparent;
      border-color: #fff;
    }

    .login-container form{
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 0 40px;
      height: 100%;
    }

    .login-container input{
      background-color: #eee;
      border: none;
      margin: 8px 0;
      padding: 10px 15px;
      font-size: 13px;
      border-radius: 8px;
      width: 100%;
      outline: none;
    }

    .form-container{
      position: absolute;
      top: 0;
      height: 100%;
      transition: all 0.6s ease-in-out;
    }

    .sign-in{
      left: 0;
      width: 50%;
      z-index: 2;
    }

    .login-container.active .sign-in{
      transform: translateX(100%);
    }

    .sign-up{
      left: 0;
      width: 50%;
      opacity: 0;
      z-index: 1;
    }

    .login-container.active .sign-up{
      transform: translateX(100%);
      opacity: 1;
      z-index: 5;
      animation: move 0.6s;
    }

    @keyframes move{
      0%, 49.99%{
        opacity: 0;
        z-index: 1;
      }
      50%, 100%{
        opacity: 1;
        z-index: 5;
      }
    }

    .gender {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      margin: 10px;
      flex-wrap: wrap;
    }

    .gender label {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .account-type {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      margin: 10px;
      flex-wrap: wrap;
    }

    .account-type label {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .checkbox {
      display: flex;
      align-items: center;
      font-size: 14px;
      gap: 10px;
      margin: 10px;
      flex-wrap: wrap;
    }

    .checkbox input {
      display: flex;
      align-items: center;
      width: auto;
    }

    .checkbox a {
      color: #512da8;
      text-decoration: none;
    }

    .toggle-container{
      position: absolute;
      top: 0;
      left: 50%;
      width: 50%;
      height: 100%;
      overflow: hidden;
      transition: all 0.6s ease-in-out;
      border-radius: 150px 0 0 100px;
      z-index: 1000;
    }

    .login-container.active .toggle-container{
      transform: translateX(-100%);
      border-radius: 0 150px 100px 0;
    }

    .toggle{
      background-color: #512da8;
      height: 100%;
      background: linear-gradient(to right, #5c6bc0, #512da8);
      color: #fff;
      position: relative;
      left: -100%;
      height: 100%;
      width: 200%;
      transform: translateX(0);
      transition: all 0.6s ease-in-out;
    }

    .login-container.active .toggle{
      transform: translateX(50%);
    }

    .toggle-panel{
      position: absolute;
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 0 30px;
      text-align: center;
      top: 0;
      transform: translateX(0);
      transition: all 0.6s ease-in-out;
    }

    .toggle-left{
      transform: translateX(-200%);
    }

    .login-container.active .toggle-left{
      transform: translateX(0);
    }

    .toggle-right{
      right: 0;
      transform: translateX(0);
    }

    .login-container.active .toggle-right{
      transform: translateX(200%);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
      .login-container {
        min-height: 580px;
      }
      
      .form-container {
        width: 100%;
      }
      
      .sign-in, .sign-up {
        width: 100%;
      }
      
      .toggle-container {
        display: none;
      }
      
      .login-container.active .sign-in {
        transform: translateX(-100%);
      }
    }
  </style>
</head>
<body>

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

  <script>
    // Login Modal Functionality
    const modal = document.getElementById('loginModal');
    const closeBtn = document.querySelector('.close-btn');
    const registerBtn = document.getElementById('register');
    const loginBackBtn = document.getElementById('login');
    const container = document.getElementById('login-container');

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

    // Handle mobile view
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