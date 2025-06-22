<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "food_waste";
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
function generateOTP($length = 6) {
    $characters = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $otp;
}

function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();                                   
        $mail->Host       = 'smtp.gmail.com';                 
        $mail->SMTPAuth   = true;                             
        $mail->Username   = 'sudipmondal704777@gmail.com';           
        $mail->Password   = 'avyj scad yvfm ekjn';              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->Port       = 587;                             
        $mail->setFrom('sudipmondal704777@gmail.com', 'Smart Food Waste Management System');
        $mail->addAddress($email);                           
        
        $mail->isHTML(true);                                 
        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Your OTP for password reset is: <b>$otp</b><br>This OTP will expire in 15 minutes.";
        $mail->AltBody = "Your OTP for password reset is: $otp\nThis OTP will expire in 15 minutes.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        $_SESSION['mail_error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

function checkEmailExists($conn, $email) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if ($stmt === false) {
        return false;
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        return 'users';
    }
    $stmt->close();
    $stmt = $conn->prepare("SELECT email FROM ngo WHERE email = ?");
    if ($stmt === false) {
        return false;
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        return 'ngo';
    }
    $stmt->close();
    
    return false;
}
if (isset($_POST['forgot_password_submit'])) {
    $email = $_POST['email'];
    $userType = checkEmailExists($conn, $email);
     if ($userType) {
        $otp = generateOTP();
        $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        if ($userType === 'users') {
            $update_stmt = $conn->prepare("UPDATE users SET reset_otp = ?, reset_otp_expiry = ? WHERE email = ?");
        } else {
            $update_stmt = $conn->prepare("UPDATE ngo SET reset_otp = ?, reset_otp_expiry = ? WHERE email = ?");
        }
        if ($update_stmt === false) {
            $error_message = "Database error: " . $conn->error;
        } else {
            $update_stmt->bind_param("sss", $otp, $expiry_time, $email);
            $update_stmt->execute();
            if (sendOTPEmail($email, $otp)) {
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_user_type'] = $userType;
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
}
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
            box-sizing: border-box;
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
        .info {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        
        <div class="info">
            <p><strong>Note:</strong> This system works for both individual users and NGO accounts. Enter your registered email address below.</p>
        </div>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="success" style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">             
                <label for="email">Email Address</label>   
                <input type="email" id="email" name="email" required placeholder="Enter your registered email">
            </div>
            <button type="submit" name="forgot_password_submit">Send OTP</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="newlogin.php">Back to Login</a>
        </p>
    </div>
</body>
</html>