<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "food_waste";

// Include PHPMailer autoload file
// You need to download PHPMailer and place it in your project
// Ensure the path below is correct for your setup
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Function to generate random OTP
function generateOTP($length = 6) {
    $characters = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $otp;
}

// Function to send email with OTP using PHPMailer
function sendOTPEmail($email, $otp) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                      // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                 // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = 'sudipmondal704777@gmail.com';           // SMTP username 
        $mail->Password   = 'avyj scad yvfm ekjn';              // SMTP password 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect 
        
        // Recipients
        $mail->setFrom('sudipmondal704777@gmail.com', 'Smart Food Waste Management System');
        $mail->addAddress($email);                            // Add a recipient
        
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Your OTP for password reset is: <b>$otp</b><br>This OTP will expire in 15 minutes.";
        $mail->AltBody = "Your OTP for password reset is: $otp\nThis OTP will expire in 15 minutes.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        // For debugging
        $_SESSION['mail_error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Process forgot password form
if (isset($_POST['forgot_password_submit'])) {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    
    // Check if prepare statement was successful
    if ($stmt === false) {
        $error_message = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate OTP
            $otp = generateOTP();
            $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            // Update database with OTP and expiry time
            $update_stmt = $conn->prepare("UPDATE users SET reset_otp = ?, reset_otp_expiry = ? WHERE email = ?");
            
            // Check if prepare statement was successful
            if ($update_stmt === false) {
                $error_message = "Database error: " . $conn->error;
            } else {
                $update_stmt->bind_param("sss", $otp, $expiry_time, $email);
                $update_stmt->execute();
                
                // Send OTP to user's email
                if (sendOTPEmail($email, $otp)) {
                    $_SESSION['reset_email'] = $email;
                    header("Location: verify_otp.php");
                    exit();
                } else {
                    if (isset($_SESSION['mail_error'])) {
                        $error_message = "Failed to send OTP: " . $_SESSION['mail_error'];
                        unset($_SESSION['mail_error']);
                    } else {
                        $error_message = "Failed to send OTP. Please try again.";
                    }
                }
                
                $update_stmt->close();
            }
        } else {
            $error_message = "Email does not exist in our records.";
        }
        
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        input[type="email"] {
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
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="success" style="color: green; margin-bottom: 15px;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">             
                <label for="email">Email</label>   
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" name="forgot_password_submit">Send OTP</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="newlogin.php">Back to Login</a>
        </p>
    </div>
</body>
</html>