<?php
// Database connection
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === "food_details") {
        // Get form data
        $donor        = trim($_POST["donor_name"]);
        $address      = trim($_POST["address"]);
        $phone        = trim($_POST["phone"]);
        $altphone     = trim($_POST["altphone"]);
        $foodname     = trim($_POST["food_name"]);
        $foodtype     = trim($_POST["food_type"]);
        $foodcategory = trim($_POST["food_category"]);
        $quantity     = trim($_POST["quantity"]);
        $unit         = trim($_POST["unit"]);

        // Image upload handling
        $imagePath = "";
        if (isset($_FILES["food_image"]) && $_FILES["food_image"]["error"] === UPLOAD_ERR_OK) {
            $uploadFolder = "admin/uploads/";                 // Folder in file system
            $savePath = "uploads/";                           // Path saved in DB (relative to admin/ folder)
            $fullPath = __DIR__ . "/" . $uploadFolder;

            // Create upload folder if not exists
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $filename = uniqid() . "_" . basename($_FILES["food_image"]["name"]);
            $targetFile = $fullPath . $filename;

            if (move_uploaded_file($_FILES["food_image"]["tmp_name"], $targetFile)) {
                $imagePath = $savePath . $filename; // Store correct relative path for DB
            } else {
                echo "<script>alert('Image upload failed.'); window.location.href = 'fooddetails.php';</script>";
                exit;
            }
        }

        // Check if user exists by phone
        $checkUser = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $checkUser->bind_param("s", $phone);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = $user["user_id"];

            // Insert food details into DB
            $insert = $conn->prepare("INSERT INTO fooddetails (
                user_id, donor_name, pickup_address, phone, alt_phone,
                food_name, food_type, food_category, quantity, unit, image
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insert->bind_param(
                "issssssssss",
                $userId, $donor, $address, $phone, $altphone,
                $foodname, $foodtype, $foodcategory, $quantity, $unit, $imagePath
            );

            if ($insert->execute()) {
                echo "<script>alert('Thank you! Food details submitted.'); window.location.href = 'fooddetails.php';</script>";
            } else {
                echo "<script>alert('Submission failed: " . $insert->error . "'); window.location.href = 'fooddetails.php';</script>";
            }

            $insert->close();
        } else {
            echo "<script>alert('Phone number not found. Please register first.'); window.location.href = 'fooddetails.php';</script>";
        }

        $checkUser->close();
    }
}

$conn->close();
?>
