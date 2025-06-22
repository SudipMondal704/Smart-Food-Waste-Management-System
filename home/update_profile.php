<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../newlogin.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$message = '';
$error = '';
$redirect_success = false;
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
if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be 10 digits!";
    } else {
        $image_name = $current_data['image']; 
         if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile_image']['type'];
            $file_size = $_FILES['profile_image']['size'];
            if (in_array($file_type, $allowed_types) && $file_size <= 5000000) { 
                $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $new_filename = $user_type . '_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = 'uploaded_img/' . $new_filename;
                if (!file_exists('uploaded_img')) {
                    mkdir('uploaded_img', 0777, true);
                }
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
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
                $redirect_success = true;
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
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #ffffff;
            
        }
        
        .update-container {
            background: #2d3748;
            border-radius: 12px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            padding: 30px;
            width: 100%;
            max-width: 550px;
            position: relative;
            border: 1px solid #4a5568;
        }
        
        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .header-left {
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
        }

        .header-left-con {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-icon {
            color: #cbd5e0;
            font-size: 18px;
        }
        
        .header-left h2 {
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .header-content p {

            color: #a0aec0;
            font-size: 13px;
            font-weight: 400;
        }
        
        .save-btn-header {
            background: #4299e1;
            color: white;
            border: none;
            margin-top: -1.1rem ;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .save-btn-header:hover {
            background: #3182ce;
        }
        
        .profile-image-section {
            margin-bottom: 15px;
        }
        
        .profile-image-label {
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }
        
        .profile-image-container {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .current-image {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4a5568;
        }
        
        .image-actions {
            display: flex;
            gap: 8px;
        }
        
        .image-btn {
            background: #4a5568;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #718096;
        }
        
        .image-btn:hover {
            background: #718096;
        }
        
        .image-upload-info {
            color: #a0aec0;
            font-size: 12px;
            margin-top: 8px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #4a5568;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.2s ease;
            background: #1a202c;
            color: #ffffff;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 70px;
        }
        
        .input-description {
            color: #a0aec0;
            font-size: 12px;
            margin-top: 4px;
            padding-left: 5px;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .file-input {
            position: absolute;
            left: -9999px;
        }
        
        .message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success {
            background: rgba(72, 187, 120, 0.1);
            color: #68d391;
            border: 1px solid rgba(72, 187, 120, 0.2);
        }
        
        .error {
            background: rgba(245, 101, 101, 0.1);
            color: #fc8181;
            border: 1px solid rgba(245, 101, 101, 0.2);
        }
        
        .btn-group {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }
        
        .btn-primary {
            background: #4299e1;
            color: white;
        }
        
        .btn-primary:hover {
            background: #3182ce;
        }
        
        .btn-secondary {
            background: #4a5568;
            color: white;
            border: 1px solid #718096;
        }
        
        .btn-secondary:hover {
            background: #718096;
        }
    </style>
</head>
<body>
    <div class="update-container">        
        <div class="form-header">
            <div class="header-left">
                <div class="header-left-con">
                    <i class="fa-regular fa-user header-icon"></i>
                    <h2>Profile information</h2>
                </div>
                <div class="header-content">
                    <p>View and update your profile information</p>
                </div>
            </div>
            <button type="submit" form="profile-form" class="save-btn-header">
                Save changes
            </button>
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
        
        <form id="profile-form" method="POST" enctype="multipart/form-data">
            <div class="profile-image-section">
                <label class="profile-image-label">Profile picture</label>
                <div class="profile-image-container">
                    <?php 
                    $image_src = '';
                    if (!empty($current_data['image'])) {
                        $file_path = 'uploaded_img/' . htmlspecialchars($current_data['image']);
                        if (file_exists($file_path)) {
                            $image_src = $file_path;
                        } else {
                            $image_src = '../img/user.png';
                        }
                    } else {
                        $image_src = '../img/user.png';
                    }
                    ?>
                    <img src="<?php echo $image_src; ?>" alt="Current Profile" class="current-image" 
                         id="profile-preview"
                         onerror="this.src='https://via.placeholder.com/64x64/4a5568/ffffff?text=<?php echo strtoupper(substr($current_data['name'], 0, 2)); ?>'">
                    
                    <div class="image-actions">
                        <div class="file-input-wrapper">
                            <input type="file" id="profile_image" name="profile_image" class="file-input" accept="image/*">
                            <button type="button" class="image-btn" onclick="document.getElementById('profile_image').click()">
                                Upload
                            </button>
                        </div>
                        <button type="button" class="image-btn" onclick="removeImage()">
                            Remove
                        </button>
                    </div>
                </div>
                <div class="image-upload-info">JPG, GIF or PNG. 1MB Max.</div>
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($current_data['name']); ?>" required>
                <div class="input-description">Will appear on receipts, invoices, and other communication</div>
            </div>
            
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_data['email']); ?>" required>
                <div class="input-description">Used to sign in, for email receipts and product updates</div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($current_data['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($current_data['address']); ?></textarea>
                </div>
            <div class="btn-group">
                <a href="homeSession.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
    
    <script>
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('profile-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        function removeImage() {
            const preview = document.getElementById('profile-preview');
            const fileInput = document.getElementById('profile_image');
            fileInput.value = '';
            preview.src = 'https://via.placeholder.com/64x64/4a5568/ffffff?text=<?php echo strtoupper(substr($current_data['name'], 0, 2)); ?>';
        }
        <?php if ($redirect_success): ?>
        const success_Message = document.querySelector('.message.success');
        if (success_Message) {
            setTimeout(() => {
                success_Message.style.opacity = '0';
                setTimeout(() => {
                    window.location.href = 'homeSession.php';
                }, 300);
            }, 2000); 
        }
        <?php else: ?>
        const successMessage = document.querySelector('.message.success');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }, 3000);
        }
        <?php endif; ?>
    </script>
</body>
</html>