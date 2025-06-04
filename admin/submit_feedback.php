<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

// Connect to database
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === "feedback") {
        $fullName = trim($_POST["full_name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phonenumber"]);
        $feedback = trim($_POST["feedback_text"]);

        // Fetch user_id if user registered
        $userId = null;
        $checkUser = $conn->prepare("SELECT user_id FROM users WHERE LOWER(email) = LOWER(?)");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = $user["user_id"];
        }
        $checkUser->close();

        // Prepare insert statement
        $insert = $conn->prepare("INSERT INTO feedback (user_id, full_name, email, phone_number, feedback_text) VALUES (?, ?, ?, ?, ?)");

        if ($userId === null) {
            // Bind NULL for user_id
            // Note: must pass variable by reference and type 'i' for int, so use null variable
            $null = NULL;
            $insert->bind_param("issss", $null, $fullName, $email, $phone, $feedback);
        } else {
            $insert->bind_param("issss", $userId, $fullName, $email, $phone, $feedback);
        }

        if ($insert->execute()) {
            echo "<script>alert('Thank you! Your feedback has been submitted.'); window.location.href = '../feedback.html';</script>";
        } else {
            echo "<script>alert('Failed to save feedback. Please try again.'); window.location.href = '../feedback.html';</script>";
        }

        $insert->close();
    }
}

$conn->close();
?>
