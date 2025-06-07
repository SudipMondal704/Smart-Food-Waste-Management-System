<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../newlogin.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$message = '';
$error = '';

// Get current user data
if ($user_type == 'Donor') {
    $query = "SELECT username as name, email, phone, address, image FROM users WHERE user_id = ?";
} elseif ($user_type == 'NGO') {
    $query = "SELECT ngo_name as name, email, phone, address, image FROM ngo WHERE ngo_id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_data = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be 10 digits!";
    } else {
        // Handle image upload
        $image_name = $current_data['image']; // Keep current image by default
        
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile_image']['type'];
            $file_size = $_FILES['profile_image']['size'];
            
            if (in_array($file_type, $allowed_types) && $file_size <= 5000000) { // 5MB limit
                $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $new_filename = $user_type . '_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = 'uploaded_img/' . $new_filename;
                
                // Create directory if it doesn't exist
                if (!file_exists('uploaded_img')) {
                    mkdir('uploaded_img', 0777, true);
                }
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if (!empty($current_data['image']) && file_exists('uploaded_img/' . $current_data['image'])) {
                        unlink('uploaded_img/' . $current_data['image']);
                    }
                    $image_name = $new_filename;
                } else {
                    $error = "Failed to upload image!";
                }
            } else {
                $error = "Invalid image file! Please upload JPG, PNG, or GIF under 5MB.";
            }
        }
        
        // Update database if no errors
        if (empty($error)) {
            if ($user_type == 'Donor') {
                $update_query = "UPDATE users SET username = ?, email = ?, phone = ?, address = ?, image = ? WHERE user_id = ?";
            } elseif ($user_type == 'NGO') {
                $update_query = "UPDATE ngo SET ngo_name = ?, email = ?, phone = ?, address = ?, image = ? WHERE ngo_id = ?";
            }
            
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssssi", $name, $email, $phone, $address, $image_name, $user_id);
            
            if ($update_stmt->execute()) {
                $message = "Profile updated successfully!";
                // Refresh current data
                $current_data['name'] = $name;
                $current_data['email'] = $email;
                $current_data['phone'] = $phone;
                $current_data['address'] = $address;
                $current_data['image'] = $image_name;
            } else {
                $error = "Failed to update profile!";
            }
            $update_stmt->close();
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
    <title>Update Profile - Food Donate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .update-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: #666;
            font-size: 14px;
        }
        
        .profile-image-section {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #34b409;
            object-fit: cover;
            margin-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
            font-size: 13px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #34b409;
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 180, 9, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 70px;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input {
            position: absolute;
            left: -9999px;
        }
        
        .file-input-label {
            display: block;
            padding: 10px 12px;
            border: 2px dashed #34b409;
            border-radius: 6px;
            background: #f0fdf4;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #34b409;
            font-size: 13px;
        }
        
        .file-input-label:hover {
            background: #dcfce7;
            border-color: #16a34a;
        }
        
        .file-input-label i {
            margin-right: 8px;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }
        
        .btn-primary {
            background: #34b409;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2a9206;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }
        
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #666;
            text-decoration: none;
            font-size: 24px;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #34b409;
        }
        
        @media (max-width: 480px) {
            .update-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
            
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="update-container">
        <a href="homeSession.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <div class="form-header">
            <h2>Update Profile</h2>
            <p>Update your information below</p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="profile-image-section">
                <?php 
                $image_src = '';
                if (!empty($current_data['image'])) {
                    $file_path = 'uploaded_img/' . htmlspecialchars($current_data['image']);
                    if (file_exists($file_path)) {
                        $image_src = $file_path;
                    } else {
                        $image_src = '../img/user.png' . strtoupper(substr($current_data['name'], 0, 2));
                    }
                } else {
                    $image_src = '../img/user/png' . strtoupper(substr($current_data['name'], 0, 2));
                }
                ?>
                <img src="<?php echo $image_src; ?>" alt="Current Profile" class="current-image" 
                     onerror="this.src='https://via.placeholder.com/80x80/34b409/ffffff?text=<?php echo strtoupper(substr($current_data['name'], 0, 2)); ?>'">
            </div>
            
            <div class="form-group">
                <label for="name"><?php echo $user_type == 'NGO' ? 'NGO Name' : 'Full Name'; ?> *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($current_data['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_data['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($current_data['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address *</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($current_data['address']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="profile_image">Profile Image (Optional)</label>
                <div class="file-input-wrapper">
                    <input type="file" id="profile_image" name="profile_image" class="file-input" accept="image/*">
                    <label for="profile_image" class="file-input-label">
                        <i class="fas fa-camera"></i>Choose New Image
                    </label>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <a href="homeSession.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
    
    <script>
        // File input preview functionality
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('.file-input-label');
            
            if (file) {
                label.innerHTML = '<i class="fas fa-check"></i> ' + file.name;
                label.style.color = '#16a34a';
                label.style.borderColor = '#16a34a';
            } else {
                label.innerHTML = '<i class="fas fa-camera"></i> Choose New Image';
                label.style.color = '#34b409';
                label.style.borderColor = '#34b409';
            }
        });
        
        // Auto-hide success message after 3 seconds
        const successMessage = document.querySelector('.message.success');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>