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
  <title>Responsive Login form</title>
  <link rel="stylesheet" href="NGOlog.css"/>
</head>
<body>
  <div class="overlay">
    <div class="signup-box">
      <div class="left-image"></div>
      <div class="right-section">
        <h2>NGO Login</h2>
        <form action="process.php" method="POST">
        <input type="hidden" name="action" value="NGOlog">
        <input type="text" name="email_phone" placeholder="Email/Phone Number" required />
          <input type="password" name="confirm_password" placeholder=" Password" required />
         
        <button type="submit">Login</button>
        </form>
        <div class="login-link">
          Don't have an account? <a href="http://localhost/php%20files/Final%20Year%20Project/Smart-Food-Waste-Management-System/NGOReg.php">Register</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>