<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

    echo "<script>
        alert('$message');
        ". (!empty($redirect) ? "window.location.href = '$redirect';" : "") ."
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Responsive Signup Form</title>
  <link rel="stylesheet" href="Registration.css"/>
</head>
<body>
  <div class="overlay">
    <div class="signup-box">
      <div class="left-image"></div>
      <div class="right-section">
        <h2>Donor Registration</h2>
        <form action="process.php" method="POST">
        <input type="hidden" name="action" value="registration">
         <input type="text" name="username"placeholder="User name" required />
          <input type="text" name="address"placeholder="Address" required />
          <input type="email" name="email"placeholder="Email" required />
          <input type="tel" name="phone" placeholder="Phone Number" required />
          <input type="password"name="password" placeholder=" Create Password" required />

          <input type="password" name="confirm_password"placeholder=" Confirm password" required />
          <div class="gender">
          <label>Gender:</label><br>
            <label><input type="radio" name="gender"value="Male" /> Male</label>
            <label><input type="radio" name="gender"value="Female"/> Female</label>
            <label><input type="radio" name="gender"value="Other" /> Other</label>
          </div>

          <div class="checkbox">
            <input type="checkbox" required />
            <span>I agree to the <a href="">terms & conditions</a></span>
          </div>
         <button type="submit">Register</button>
        </form>
        <div class="login-link">
          Already have an account? <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/login.php">Login</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>