<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Responsive Signup Form</title>
  <link rel="stylesheet" href="usersignup.css" />
</head>
<body>
  <div class="overlay">
    <div class="signup-box">
      <div class="left-image"></div>
      <div class="right-section">
        <h2>Sign Up</h2>
        <form>
          <input type="text" placeholder="User Name" required />
          <input type="text" placeholder="Address" required />
          <input type="email" placeholder="Email" required />
          <input type="tel" placeholder="Phone Number" required />
          <input type="password" placeholder=" Create Password" required />
          <input type="password" placeholder=" Confirm password" required />


          <div class="gender">
          <label>Gender:</label>
            <label><input type="radio" name="gender" /> Male</label>
            <label><input type="radio" name="gender" /> Female</label>
            <label><input type="radio" name="gender" /> Other</label>
          </div>

          <div class="checkbox">
            <input type="checkbox" required />
            <span>I agree to the <a href="#">terms & conditions</a></span>
          </div>

          <button type="submit">Register</button>
        </form>
        <div class="login-link">
          Already have an account? <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/registration/userlogin.php">Login</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>