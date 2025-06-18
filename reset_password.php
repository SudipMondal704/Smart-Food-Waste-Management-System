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

// Check if user is authenticated for password reset
if (!isset($_SESSION['reset_authenticated']) || !isset($_SESSION['reset_email']) || !isset($_SESSION['reset_user_type'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$userType = $_SESSION['reset_user_type'];
$error_message = "";
$success_message = "";

// Process password reset form
if (isset($_POST['reset_password_submit'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password strength
    if (strlen($new_password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Store password as plain text (WARNING: This is not secure!)
        $password = $new_password;
        
        // Update password in the appropriate table based on user type
        if ($userType === 'users') {
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_otp = NULL, reset_otp_expiry = NULL WHERE email = ?");
        } else {
            $stmt = $conn->prepare("UPDATE ngo SET password = ?, reset_otp = NULL, reset_otp_expiry = NULL WHERE email = ?");
        }
        
        if ($stmt === false) {
            $error_message = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $password, $email);
            
            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->affected_rows > 0) {
                    $success_message = "Password has been reset successfully for " . ($userType === 'users' ? 'User' : 'NGO') . " account.";
                    
                    // Clear reset session variables
                    unset($_SESSION['reset_authenticated']);
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_user_type']);
                    
                    // Auto-redirect after successful password reset
                    header("refresh:3;url=newlogin.php");
                } else {
                    $error_message = "No account found or password not updated. Please try again.";
                }
            } else {
                $error_message = "Failed to reset password: " . $stmt->error;
            }
            
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        input[type="password"] {
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
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
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
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .security-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
            border-left: 4px solid #ffc107;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <div class="security-warning">
            <strong>Security Notice:</strong> This system stores passwords in plain text, which is not secure. Consider implementing proper password hashing for production use.
        </div>
        
        <div class="info">
            <p>Resetting password for: <strong><?php echo htmlspecialchars($email); ?></strong></p>
            <p>Account Type: <span class="account-type"><?php echo $userType === 'users' ? 'Individual User' : 'NGO'; ?></span></p>
        </div>
        
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message) && !empty($success_message)): ?>
            <div class="success">
                <?php echo htmlspecialchars($success_message); ?>
                <p>Redirecting to login page...</p>
            </div>
        <?php else: ?>
            <form method="post" action="" id="resetPasswordForm">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required onkeyup="checkPasswordStrength()"> 

                    <div class="password-requirements">
                        Password must be at least 8 characters long and include a mix of letters, numbers, and special characters...
                    </div>
                    <div id="password-strength" class="password-strength"></div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required onkeyup="checkPasswordMatch()">
                    <div id="password-match" class="password-strength"></div>
                </div>
                <button type="submit" name="reset_password_submit" id="submit-btn">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
    
    <script>
        function checkPasswordStrength() {
            var password = document.getElementById("new_password").value;
            var strength = 0;
            var strengthText = "";
            var strengthColor = "";
            
            // Check password length
            if (password.length >= 8) {
                strength += 1;
            }
            
            // Check for numbers
            if (/\d/.test(password)) {
                strength += 1;
            }
            
            // Check for lowercase
            if (/[a-z]/.test(password)) {
                strength += 1;
            }
            
            // Check for uppercase
            if (/[A-Z]/.test(password)) {
                strength += 1;
            }
            
            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 1;
            }
            
            // Set strength text and color
            switch(strength) {
                case 0:
                case 1:
                    strengthText = "Very Weak";
                    strengthColor = "red";
                    break;
                case 2:
                    strengthText = "Weak";
                    strengthColor = "orange";
                    break;
                case 3:
                    strengthText = "Medium";
                    strengthColor = "orange";
                    break;
                case 4:
                    strengthText = "Strong";
                    strengthColor = "green";
                    break;
                case 5:
                    strengthText = "Very Strong";
                    strengthColor = "green";
                    break;
            }
            
            document.getElementById("password-strength").textContent = "Password Strength: " + strengthText;
            document.getElementById("password-strength").style.color = strengthColor;
            
            // Also check password match when strength changes
            checkPasswordMatch();
        }
        
        function checkPasswordMatch() {
            var password = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var matchElement = document.getElementById("password-match");
            var submitBtn = document.getElementById("submit-btn");
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchElement.textContent = "Passwords match ✓";
                    matchElement.style.color = "green";
                    submitBtn.disabled = false;
                } else {
                    matchElement.textContent = "Passwords do not match ✗";
                    matchElement.style.color = "red";
                    submitBtn.disabled = true;
                }
            } else {
                matchElement.textContent = "";
                submitBtn.disabled = false;
            }
        }
        
        // Form validation
        document.getElementById("resetPasswordForm").addEventListener("submit", function(event) {
            var password = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            
            if (password.length < 8) {
                alert("Password must be at least 8 characters long!");
                event.preventDefault();
                return;
            }
            
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>