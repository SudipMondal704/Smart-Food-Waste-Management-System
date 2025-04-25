<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Signup Form</title>
  <link rel="stylesheet" href="userReg.css">
</head>
<body>
  <div class="overlay">
    <div class="signup-box">
      <div class="left-image"></div>
      <div class="right-section">
        <h2>Registration</h2>
        <form action="#" method="post">
          <input type="text" placeholder="Username" required />
          <input type="text" placeholder="Address" required />
          <input type="email"placeholder="Email" required/>

          <div class="gender">
            <label>Gender:</label>
            <label><input type="radio" name="gender" value="male" required /> Male</label>
            <label><input type="radio" name="gender" value="female" required /> Female</label>
            <label><input type="radio" name="gender" value="other" required /> Other</label>
          </div>

          <input type="tel" placeholder="Phone Number" required />
          <input type="password" placeholder="Create Password" required />
          <input type="password" placeholder="Confirm Password" required />
          
          <div class="checkbox">
            <input type="checkbox" id="terms" required />
            <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
          </div>

          <button type="submit">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/Registration/userlogin.php">Login</a></p>
      </div>
    </div>
  </div>
</body>
</html>
