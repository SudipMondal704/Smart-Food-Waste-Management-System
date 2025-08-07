<?php
session_start();
if (!isset($_SESSION['user_id'])) {
   echo "<script>alert('Login as Donor to send feedback!'); window.location.href='newlogin.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['action']) && $_GET['action'] === 'submit') {
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "food_waste";

    $conn = new mysqli($server, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $action = $_POST["action"] ?? '';

    if ($action === "feedback") {
        // Get and sanitize input data
        $fullName = trim($_POST["full_name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phonenumber"]);
        $feedback = trim($_POST["feedback_text"]);
        
        // Validate required fields
        if (empty($fullName) || empty($email) || empty($phone) || empty($feedback)) {
            echo "<script>alert('All fields are required. Please fill out the form completely.'); window.location.href = 'feedback.php';</script>";
            exit();
        }
        
        $userId = null;
        
        // Check if user exists in the database
        $checkUser = $conn->prepare("SELECT user_id FROM users WHERE LOWER(email) = LOWER(?)");
        if ($checkUser) {
            $checkUser->bind_param("s", $email);
            $checkUser->execute();
            $result = $checkUser->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $userId = $user["user_id"];
            }
            $checkUser->close();
        }
        
        // Insert feedback into database
        $insert = $conn->prepare("INSERT INTO feedback (user_id, full_name, email, phone_number, feedback_text, feedback_date) VALUES (?, ?, ?, ?, ?, NOW())");
        
        if ($insert) {
            if ($userId === null) {
                // If user doesn't exist, insert with NULL user_id
                $insert->bind_param("issss", $userId, $fullName, $email, $phone, $feedback);
            } else {
                // If user exists, insert with user_id
                $insert->bind_param("issss", $userId, $fullName, $email, $phone, $feedback);
            }

            if ($insert->execute()) {
                echo "<script>alert('Thank you! Your feedback has been submitted successfully.'); window.location.href = 'feedback.php';</script>";
            } else {
                echo "<script>alert('Failed to save feedback. Error: " . $conn->error . "'); window.location.href = 'feedback.php';</script>";
            }
            $insert->close();
        } else {
            echo "<script>alert('Database error: Could not prepare statement.'); window.location.href = 'feedback.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid action.'); window.location.href = 'feedback.php';</script>";
    }
    
    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donors Feedback Form</title>
  <link rel="stylesheet" href="feedback.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
</head>
<body>
  <div class="container">
    <div class="return" style="text-align: left; background-color: white; color: #252525; border-radius: 5px; padding: 10px 15px;">
            <a href="home/homeSession.php" style="text-decoration: none; background-color:#c1c0c0; color: #252525; border-radius: 5px; padding: 10px 15px;"><i class="fi fi-ss-angle-double-small-left"></i> Back to home</a>
        </div>
    <div class="logo">
      <img src="img/feedback.png" alt="logo" />
    </div>
    <div class="title">Donors Feedback Form</div>
    <div class="subtitle">
      Thank you for taking time to provide feedback. We appreciate hearing from you and will review your comments carefully.
    </div>
    <div class="content">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=submit" method="POST">
        <input type="hidden" name="action" value="feedback" />
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="full_name" placeholder="Enter your name" required />
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Enter your email" required />
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="phonenumber" placeholder="Enter your Phone Number" required />
          </div>
        </div>
        <div class="message-box">
          <span class="details">Do you have any suggestions to improve our service?</span>
          <textarea name="feedback_text" placeholder="Write your feedback" required></textarea>
        </div>
        <div class="button">
          <input type="submit" value="Send Feedback" />
        </div>
      </form>
    </div>
  </div>
</body>
</html>