<?php
// DB connection
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
        // Get all input fields
        $donor = trim($_POST["donor_name"]);
        $address = trim($_POST["address"]);
        $phone = trim($_POST["phone"]);
        $altphone = trim($_POST["altphone"]);
        $foodname = trim($_POST["food_name"]);
        $foodtype = trim($_POST["food_type"]);
        $foodcategory = trim($_POST["food_category"]);
        $quantity = trim($_POST["quantity"]);
        $unit = trim($_POST["unit"]);

        // Handle image file
        $imageData = null;
        if (isset($_FILES["food_image"]) && $_FILES["food_image"]["error"] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES["food_image"]["tmp_name"]);
        }

        // Get user_id from phone
        $checkUser = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $checkUser->bind_param("s", $phone);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = $user["user_id"];

            // Prepare insert query
            $insert = $conn->prepare("INSERT INTO fooddetails (
                user_id, donor_name, pickup_address, phone, alternate_phone,
                food_name, food_type, food_category, quantity, unit, image
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $null = NULL; // placeholder for blob

            $insert->bind_param(
                "isssssssssb",
                $userId, $donor, $address, $phone, $altphone,
                $foodname, $foodtype, $foodcategory, $quantity, $unit, $null
            );

            if ($imageData !== null) {
                $insert->send_long_data(10, $imageData); // index 10 = 11th param
            }

            // Execute and check
            if ($insert->execute()) {
                echo "<script>alert('Thank you! Food details submitted.'); window.location.href = 'fooddetails.php';</script>";
            } else {
                echo "<script>alert('Failed to submit: " . $insert->error . "'); window.location.href = 'fooddetails.php';</script>";
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
