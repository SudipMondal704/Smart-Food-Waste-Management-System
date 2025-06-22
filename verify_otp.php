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
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_user_type'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$userType = $_SESSION['reset_user_type'];
$error_message = "";
$success_message = "";
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
        
        $mail->setFrom('sudipmondal704777@gmail.com', 'Easy Donate');
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
if (isset($_POST['verify_otp_submit'])) {
    $user_otp = $_POST['otp'];
    if ($userType === 'users') {
        $stmt = $conn->prepare("SELECT reset_otp, reset_otp_expiry FROM users WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT reset_otp, reset_otp_expiry FROM ngo WHERE email = ?");
    }
    
    if ($stmt === false) {
        $error_message = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stored_otp = $user['reset_otp'];
            $expiry_time = $user['reset_otp_expiry'];
            if (strtotime($expiry_time) < time()) {
                $error_message = "OTP has expired. Please request a new one.";
            } 
            else if ($stored_otp == $user_otp) {
                $_SESSION['reset_authenticated'] = true;
                header("Location: reset_password.php");
                exit();
            } else {
                $error_message = "Invalid OTP. Please try again.";
            }
        } else {
            $error_message = "User not found.";
        }
        
        $stmt->close();
    }
}
if (isset($_POST['resend_otp'])) {
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
            $success_message = "A new OTP has been sent to your email.";
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
}
$conn->close();
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
            box-sizing: border-box;
            text-align: center;
            font-size: 18px;
            letter-spacing: 2px;
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
            background-color: #ffebee;
            padding: 10px;
            border-radius: 3px;
            border-left: 4px solid #f44336;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            background-color: #f3e5f5;
            padding: 10px;
            border-radius: 3px;
            border-left: 4px solid #4CAF50;
        }
        .info {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .account-type {
            display: inline-block;
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        
        <div class="info">
            <p>We've sent an OTP to your email address: <strong><?php echo htmlspecialchars($email); ?></strong></p>
            <p>Account Type: <span class="account-type"><?php echo $userType === 'users' ? 'Donor' : 'NGO'; ?></span></p>
        </div>
        
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message) && !empty($success_message)): ?>
            <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="otp">Enter 6-Digit OTP</label>
                <input type="text" id="otp" name="otp" maxlength="6" required placeholder="000000" pattern="[0-9]{6}" title="Please enter a 6-digit number">
            </div>
            <button type="submit" name="verify_otp_submit">Verify OTP</button>
            <button type="submit" name="resend_otp" class="resend-btn">Resend New OTP</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px ;">
            <a href="forgot_password.php"> ← Back to Forgot Password </a>
        </p>
    </div>
     <script>
        document.getElementById('otp').focus();
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        document.getElementById('otp').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                setTimeout(() => {
                    document.querySelector('button[name="verify_otp_submit"]').click();
                }, 500);
            }
        });
    </script>
</body>
</html>