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
if (!isset($_SESSION['reset_authenticated']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error_message = "";
$success_message = "";

// Process password reset form
if (isset($_POST['reset_password_submit'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Store the original password directly (as requested)
        $password = $new_password;
        
        // Update password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_otp = NULL, reset_otp_expiry = NULL WHERE email = ?");
        $stmt->bind_param("ss", $password, $email);
        
        if ($stmt->execute()) {
            $success_message = "Password has been reset successfully.";
            
            // Clear reset session variables
            unset($_SESSION['reset_authenticated']);
            unset($_SESSION['reset_email']);
            
            // Auto-redirect after successful password reset (optional)
            header("refresh:3;url=newlogin.php");
        } else {
            $error_message = "Failed to reset password. Please try again.";
        }
    }
}
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
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message) && !empty($success_message)): ?>
            <div class="success">
                <?php echo $success_message; ?>
                <p>Redirecting to login page...</p>
            </div>
        <?php else: ?>
            <form method="post" action="" id="resetPasswordForm">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required onkeyup="checkPasswordStrength()">
                    <div id="password-strength" class="password-strength"></div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="reset_password_submit">Reset Password</button>
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
                    strengthText = "Weak";
                    strengthColor = "red";
                    break;
                case 2:
                case 3:
                    strengthText = "Medium";
                    strengthColor = "orange";
                    break;
                case 4:
                case 5:
                    strengthText = "Strong";
                    strengthColor = "green";
                    break;
            }
            
            document.getElementById("password-strength").textContent = "Password Strength: " + strengthText;
            document.getElementById("password-strength").style.color = strengthColor;
        }
        
        // Form validation
        document.getElementById("resetPasswordForm").addEventListener("submit", function(event) {
            var password = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                event.preventDefault();
            }
        });
    </script>
</body>
</html>