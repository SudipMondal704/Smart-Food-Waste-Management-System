<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>login Form</title>
  <link rel="stylesheet" href="usersignup.css">
</head>
<body>
  <div class="container">
  <form class="signup-form" action="signup.php" method="POST">
     <strong><h2>Signup</h2></strong> 
      <input type="text" name="username" placeholder="Username" required>
      <input type="text" name="Address" placeholder="Address" required>
      <label>Select Gender:</label>
      <input type="radio" name="gender" value="male">
      <label for="male">Male</label>
      <input type="radio" name="gender" value="female">
      <label for="female">Female</label>
      <input type="radio" name="gender" value="other">
      <label for="other">Other</label>
      <input type="text" name="Phone Number" placeholder="Phone Number" required>
      <input type="email" name="Email" placeholder="Email" required>
      <input type="password" name="Password" placeholder="Password" required>
      <input type="password" name="Confirm_password" placeholder="Confirm Password" required>
      <button type="submit" name="register">Register</button>
      <p class="login-link">Already have an account? <a href="http://localhost/foodWaste/Smart-Food-Waste-Management-System/userLogin.php">Login</a></p>
    </form>



  </div>
</body>
</html>
