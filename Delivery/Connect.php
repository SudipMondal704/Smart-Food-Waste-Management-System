<?php
session_start();

$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, 'food_waste');

$msg = 0;
if (isset($_POST['sign'])) {
  $email = $_POST['email'];
  $password = $_POST['password']; // Raw password from form
  
  // Only sanitize the email for the query, not the password
  $sanitized_email = mysqli_real_escape_string($connection, $email);
  
  // Use prepared statements instead for better security
  $stmt = $connection->prepare("SELECT * FROM admin WHERE email = ?");
  $stmt->bind_param("s", $sanitized_email);
  $stmt->execute();
  $result = $stmt->get_result();
  $num = $result->num_rows;
 
  if ($num == 1) {
    $row = $result->fetch_assoc();
    // Don't sanitize password before verification
    if (password_verify($password, $row['password'])) {
      $_SESSION['email'] = $email;
      $_SESSION['name'] = $row['name'];
      $_SESSION['location'] = $row['location'];
      $_SESSION['Aid'] = $row['Aid'];
      header("location:admin.php");
      exit(); // Always exit after redirect
    } else {
      $msg = 1; // Invalid password
    }
  } else {
    // Use a generic error message for security
    $msg = 2; // Account not found
  }
}

// Display error messages in a more secure way
if ($msg == 1) {
  $error_message = "Invalid email or password.";
} else if ($msg == 2) {
  $error_message = "Invalid email or password.";
}
?>