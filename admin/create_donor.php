<?php
require_once('adminSession.php');

// create_donor.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message_type = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name     = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $password = $_POST['password'];

    // Image Upload
    $image_name     = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size     = $_FILES['image']['size'];
    
    // Create the upload directory if it doesn't exist
    $upload_dir = "home/uploaded_img/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $unique_name = '';
    
    // Basic image validation
    if (!empty($image_name)) {
        // Check file size (max 2MB)
        if ($image_size > 2000000) {
            $message = "Image size is too large. Max 2MB allowed.";
            $message_type = "error";
        } else {
            // Check file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            if (!in_array($file_extension, $allowed_types)) {
                $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
                $message_type = "error";
            } else {
                // Generate unique filename to avoid conflicts
                $unique_name = time() . '_' . $image_name;
                $image_folder = $upload_dir . $unique_name;
            }
        }
    }

    // Proceed if no image errors
    if (empty($message)) {
        // Check if email already exists
        $check_query = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check_query->bind_param("s", $email);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows > 0) {
            $message = "Email already registered!";
            $message_type = "error";
        } else {
            // Insert donor into users table
            $stmt = $conn->prepare("INSERT INTO users (username, address, email, phone, password, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $name, $address, $email, $phone, $password, $unique_name);

            if ($stmt->execute()) {
                // Only move file if upload was successful and file exists
                if (!empty($unique_name) && !empty($image_tmp_name)) {
                    if (move_uploaded_file($image_tmp_name, $image_folder)) {
                        $message = "Donor added successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Donor added successfully but image upload failed.";
                        $message_type = "warning";
                    }
                } else {
                    $message = "Donor added successfully!";
                    $message_type = "success";
                }
                
                // Clear form data on success
                if ($message_type == "success") {
                    $name = $email = $phone = $address = $password = "";
                }
            } else {
                $message = "Error adding donor: " . $stmt->error;
                $message_type = "error";
            }

            $stmt->close();
        }
        $check_query->close();
    }
}

$conn->close();
?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .create-donor-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-title {
        color: #333;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #007bff;
        font-size: 24px;
        font-weight: bold;
    }
    
    .donor-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-group label {
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group textarea {
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .file-upload-wrapper {
        position: relative;
    }
    
    .file-upload-label {
        display: block;
        padding: 12px;
        background: #f8f9fa;
        border: 2px dashed #ddd;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        color: #666;
    }
    
    .file-upload-label:hover {
        background: #e9ecef;
        border-color: #007bff;
        color: #007bff;
    }
    
    .file-upload-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .file-name {
        margin-top: 8px;
        font-size: 12px;
        color: #666;
        font-style: italic;
    }
    
    .button-group {
        grid-column: 1 / -1;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
        transform: translateY(-1px);
    }
    
    .message {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 500;
    }
    
    .message.success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .message.error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .message.warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }
    
    @media (max-width: 768px) {
        .donor-form {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .button-group {
            flex-direction: column;
        }
        
        .create-donor-container {
            padding: 20px;
        }
    }
</style>

<div class="create-donor-container">
    <h2 class="form-title">
        <i class="bx bxs-user ngo-icon"></i>
        Add New Donor
    </h2>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="donor-form">
        <div class="form-group">
            <label for="username">Full Name *</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="tel" id="phone" name="phone" required 
                   value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>
        
        <div class="form-group full-width">
            <label for="address">Address *</label>
            <textarea id="address" name="address" required placeholder="Enter full address"><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></textarea>
        </div>
        
        <div class="form-group full-width">
            <label for="image">Profile Image</label>
            <div class="file-upload-wrapper">
                <input type="file" id="image" name="image" accept="image/*" class="file-upload-input" onchange="showFileName(this)">
                <label for="image" class="file-upload-label">
                    <i class="fi fi-ss-camera" style="margin-right: 8px;"></i>
                    Choose Image File (Max 2MB)
                </label>
                <div class="file-name" id="fileName">No file chosen</div>
            </div>
        </div>
        
        <div class="button-group">
            <a href="admin.php?type=donors" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Donor</button>
        </div>
    </form>
</div>

<script>
    function showFileName(input) {
        const fileName = document.getElementById('fileName');
        if (input.files.length > 0) {
            fileName.textContent = input.files[0].name;
        } else {
            fileName.textContent = 'No file chosen';
        }
    }
</script>