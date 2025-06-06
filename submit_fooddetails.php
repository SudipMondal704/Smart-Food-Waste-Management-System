<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === "food_details") {
        $donor    = trim($_POST["donor_name"]);
        $address  = trim($_POST["address"]);
        $phone    = trim($_POST["phone"]);
        $altphone = trim($_POST["altphone"]);

        // Validate required fields
        if (empty($donor) || empty($address) || empty($phone) || empty($altphone)) {
            echo "<script>alert('Please fill all required contact details.'); window.location.href = 'fooddetails.php';</script>";
            exit;
        }

        // Check if user is already registered
        $checkUser = $conn->prepare("SELECT user_id, username FROM users WHERE phone = ?");
        $checkUser->bind_param("s", $phone);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = $user["user_id"];
            $username = $user["username"];

            // Insert submission record
            $subInsert = $conn->prepare("INSERT INTO submissions (user_id, donor_name, pickup_address, phone, alt_phone, submission_time) VALUES (?, ?, ?, ?, ?, NOW())");
            $subInsert->bind_param("issss", $userId, $donor, $address, $phone, $altphone);
            
            if (!$subInsert->execute()) {
                echo "<script>alert('Failed to create submission record: " . $subInsert->error . "'); window.location.href = 'fooddetails.php';</script>";
                exit;
            }
            
            $submission_id = $conn->insert_id;
            $subInsert->close();

            // Process food items
            $food_names      = $_POST['food_name'] ?? [];
            $quantities      = $_POST['quantity'] ?? [];
            $units           = $_POST['unit'] ?? [];
            $images          = $_FILES['food_image'] ?? [];

            if (empty($food_names)) {
                echo "<script>alert('Please add at least one food item.'); window.location.href = 'fooddetails.php';</script>";
                exit;
            }

            $total_items = count($food_names);
            $successful_inserts = 0;

            for ($i = 0; $i < $total_items; $i++) {
                // Get food type for this item
                $food_type = '';
                $food_type_key = 'food_type_' . ($i + 1);
                if (isset($_POST[$food_type_key])) {
                    $food_type = trim($_POST[$food_type_key]);
                }

                // Get food category for this item
                $food_category = '';
                $food_category_key = 'food_category_' . ($i + 1);
                if (isset($_POST[$food_category_key])) {
                    $food_category = trim($_POST[$food_category_key]);
                }

                $food_name = trim($food_names[$i]);
                $quantity  = trim($quantities[$i]);
                $unit      = trim($units[$i]);

                // Validate required fields for this food item
                if (empty($food_name) || empty($quantity) || empty($unit)) {
                    echo "<script>alert('Please fill all required fields for food item " . ($i + 1) . ".'); window.location.href = 'fooddetails.php';</script>";
                    exit;
                }

                // Handle image upload
                $imagePath = "";
                if (isset($images["name"][$i]) && !empty($images["name"][$i]) && $images["error"][$i] === UPLOAD_ERR_OK) {
                    $uploadFolder = "admin/uploads/";
                    $savePath     = "uploads/";
                    $fullPath     = __DIR__ . "/" . $uploadFolder;

                    // Create upload directory if it doesn't exist
                    if (!is_dir($fullPath)) {
                        if (!mkdir($fullPath, 0777, true)) {
                            echo "<script>alert('Failed to create upload directory.'); window.location.href = 'fooddetails.php';</script>";
                            exit;
                        }
                    }

                    // Generate unique filename
                    $fileExtension = strtolower(pathinfo($images["name"][$i], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        echo "<script>alert('Invalid file type for food item " . ($i + 1) . ". Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.'); window.location.href = 'fooddetails.php';</script>";
                        exit;
                    }

                    // Check file size (limit to 5MB)
                    if ($images["size"][$i] > 5 * 1024 * 1024) {
                        echo "<script>alert('File size too large for food item " . ($i + 1) . ". Maximum size is 5MB.'); window.location.href = 'fooddetails.php';</script>";
                        exit;
                    }

                    $filename = uniqid() . "_" . time() . "." . $fileExtension;
                    $targetFile = $fullPath . $filename;

                    if (move_uploaded_file($images["tmp_name"][$i], $targetFile)) {
                        $imagePath = $savePath . $filename;
                    } else {
                        echo "<script>alert('Image upload failed for food item " . ($i + 1) . ".'); window.location.href = 'fooddetails.php';</script>";
                        exit;
                    }
                }

                // Insert into fooddetails table with donor details as pickup details
                $insert = $conn->prepare("INSERT INTO fooddetails (
                    submission_id, user_id, donor_name, pickup_address, phone, alt_phone,
                    food_name, food_type, food_category, quantity, unit, image, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

                $insert->bind_param(
                    "iissssssssss",
                    $submission_id, $userId, $donor, $address, $phone, $altphone,
                    $food_name, $food_type, $food_category, $quantity, $unit, $imagePath
                );

                if ($insert->execute()) {
                    $successful_inserts++;
                } else {
                    echo "<script>alert('Failed to insert food item " . ($i + 1) . ": " . $insert->error . "'); window.location.href = 'fooddetails.php';</script>";
                    exit;
                }

                $insert->close();
            }

            if ($successful_inserts === $total_items) {
                echo "<script>alert('Food Donation Request submitted successfully! Total items: $successful_inserts'); window.location.href = 'fooddetails.php';</script>";
            } else {
                echo "<script>alert('Partial success: $successful_inserts out of $total_items items were saved.'); window.location.href = 'fooddetails.php';</script>";
            }

        } else {
            echo "<script>alert('Phone number not found. Please register first.'); window.location.href = 'fooddetails.php';</script>";
        }

        $checkUser->close();
    } else {
        echo "<script>alert('Invalid action.'); window.location.href = 'fooddetails.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location.href = 'fooddetails.php';</script>";
}

$conn->close();
?>