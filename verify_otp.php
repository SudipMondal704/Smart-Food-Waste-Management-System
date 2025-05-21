<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "food_waste";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Check if user came from forgot password page
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error_message = "";
$success_message = "";

// Process OTP verification
if (isset($_POST['verify_otp_submit'])) {
    $user_otp = $_POST['otp'];
    
    // Get stored OTP details from database
    $stmt = $conn->prepare("SELECT reset_otp, reset_otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_otp = $user['reset_otp'];
        $expiry_time = $user['reset_otp_expiry'];
        
        // Check if OTP is expired
        if (strtotime($expiry_time) < time()) {
            $error_message = "OTP has expired. Please request a new one.";
        } 
        // Verify OTP
        else if ($stored_otp == $user_otp) {
            // OTP is valid, set session variable for password reset
            $_SESSION['reset_authenticated'] = true;
            header("Location: reset_password.php");
            exit();
        } else {
            $error_message = "Invalid OTP. Please try again.";
        }
    } else {
        $error_message = "User not found.";
    }
}

// Process resend OTP
if (isset($_POST['resend_otp'])) {
    // Generate new OTP
    $otp = generateOTP();
    $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // Update database with new OTP and expiry time
    $update_stmt = $conn->prepare("UPDATE users SET reset_otp = ?, reset_otp_expiry = ? WHERE email = ?");
    $update_stmt->bind_param("sss", $otp, $expiry_time, $email);
    $update_stmt->execute();
    
    // Send new OTP to user's email
    if (sendOTPEmail($email, $otp)) {
        $success_message = "A new OTP has been sent to your email.";
    } else {
        $error_message = "Failed to send OTP. Please try again.";
    }
}

// Function to generate random OTP (copied from forgot_password.php)
function generateOTP($length = 6) {
    $characters = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $otp;
}

// Function to send email with OTP (copied from forgot_password.php)
function sendOTPEmail($email, $otp) {
    $subject = "Password Reset OTP";
    $message = "Your OTP for password reset is: $otp\n";
    $message .= "This OTP will expire in 15 minutes.";
    $headers = "From: noreply@yourwebsite.com\r\n";
    
    // For production, use a proper email library like PHPMailer
    return mail($email, $subject, $message, $headers);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .resend-btn {
            background-color: #2196F3;
        }
        .resend-btn:hover {
            background-color: #0b7dda;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <p>We've sent an OTP to your email address: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message) && !empty($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" id="otp" name="otp" maxlength="6" required>
            </div>
            <button type="submit" name="verify_otp_submit">Verify OTP</button>
            <button type="submit" name="resend_otp" class="resend-btn">Resend OTP</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="forgot_password.php">Back to Forgot Password</a>
        </p>
    </div>
</body>
</html>