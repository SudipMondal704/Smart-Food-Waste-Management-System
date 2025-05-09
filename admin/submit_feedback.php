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

    if ($action === "feedback") {
        $fullName = trim($_POST["full_name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phonenumber"]);
        $feedback = trim($_POST["feedback_text"]);

        // Fetch user_id using email
        $checkUser = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = $user["user_id"];

            $insert = $conn->prepare("INSERT INTO feedback (user_id, full_name, email, phone_number, feedback_text)
                                      VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("issss", $userId, $fullName, $email, $phone, $feedback);

            if ($insert->execute()) {
                echo "<script>alert('Thank you! Your feedback has been submitted.'); window.location.href = 'feedback.html';</script>";
            } else {
                echo "<script>alert('Failed to save feedback.'); window.location.href = 'feedback.html';</script>";
            }

            $insert->close();
        } else {
            echo "<script>alert('Email not found. Please register first.'); window.location.href = 'feedback.html';</script>";
        }

        $checkUser->close();
    }
}

$conn->close();
?>
