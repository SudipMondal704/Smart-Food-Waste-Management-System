<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['username']);
    $type     = $_POST['acc-type'];
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $password = $_POST['password'];
    $image_name     = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size     = $_FILES['image']['size'];
    $upload_dir = "home/uploaded_img/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $image_folder = $upload_dir . $image_name;
    if (!empty($image_name)) {
        if ($image_size > 2000000) {
            echo "<script>alert('Image size is too large. Max 2MB allowed.'); window.location.href='newregister.php';</script>";
            exit();
        }
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='newregister.php';</script>";
            exit();
        }
        $unique_name = time() . '_' . $image_name;
        $image_folder = $upload_dir . $unique_name;
    } else {
        $unique_name = '';
    }
    if ($type == "Donor") {
        $check_query = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    } elseif ($type == "NGO") {
        $check_query = $conn->prepare("SELECT ngo_id FROM ngo WHERE email = ?");
    } else {
        die("Invalid account type.");
    }

    $check_query->bind_param("s", $email);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='newregister.php';</script>";
    } else {
        if ($type == "Donor") {
            $stmt = $conn->prepare("INSERT INTO users (username, address, email, phone, password, image) VALUES (?, ?, ?, ?, ?, ?)");
            $res = mysqli_query($conn,"INSERT INTO notification set title='New User Signup', details='".$_POST['username']." has signed up in your portal',date='".date('Y-m-d')."',time='".date('H:i:s')."'  ");
        } else {
            $stmt = $conn->prepare("INSERT INTO ngo (ngo_name, address, email, phone, password, image) VALUES (?, ?, ?, ?, ?, ?)");
              $res = mysqli_query($conn,"INSERT INTO notification set title='New NGO Signup', details='".$_POST['username']." has signed up in your portal',date='".date('Y-m-d')."',time='".date('H:i:s')."'  ");
        }

        $stmt->bind_param("ssssss", $name, $address, $email, $phone, $password, $unique_name);

        if ($stmt->execute()) {
            if (!empty($image_name) && !empty($image_tmp_name)) {
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    echo "<script>alert('Registration Successful! Please Login.'); window.location.href='newlogin.php';</script>";
                } else {
                    echo "<script>alert('Registration successful but image upload failed.'); window.location.href='newlogin.php';</script>";
                }
            } else {
                echo "<script>alert('Registration Successful! Please Login.'); window.location.href='newlogin.php';</script>";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_query->close();
}

$conn->close();
?>