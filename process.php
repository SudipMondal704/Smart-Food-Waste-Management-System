<?php


// Hardcoded Admin credentials


// Hardcoded admin credentials (email or phone => hashed password)

$admin_credentials = [
    // Admin 1
    "rayantan@gmail.com" => password_hash("rayantan", PASSWORD_DEFAULT),
    "1111" => password_hash("rayantan", PASSWORD_DEFAULT),

    // Admin 2
    "sudip@gmail.com" => password_hash("sudip", PASSWORD_DEFAULT),
    "2222" => password_hash("sudip", PASSWORD_DEFAULT),

    // Admin 3
    "anjan@gmail.com" => password_hash("anjan", PASSWORD_DEFAULT),
    "3333" => password_hash("anjan", PASSWORD_DEFAULT),

    // Admin 4
    "rintu@gmail.com" => password_hash("rintu", PASSWORD_DEFAULT),
    "4444" => password_hash("rintu", PASSWORD_DEFAULT),
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // -------------------------- USER REGISTRATION ---------------------------- //
    if ($action == "registration") {
        $username = $_POST['username'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $gender = $_POST['gender'] ?? '';

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, address, email, phone, password, gender) 
                    VALUES ('$username', '$address', '$email', '$phone', '$hashed_password', '$gender')";

            if ($conn->query($sql) === TRUE) {
                header("Location: registration.php?message=Registration successful!&redirect=login.php");
                exit();
            } else {
                header("Location: registration.php?message=Registration failed: " . urlencode($conn->error));
                exit();
            }
        } else {
            header("Location: registration.php?message=Passwords do not match!");
            exit();
        }

    // -------------------------- USER & ADMIN LOGIN ---------------------------- //
    } elseif ($action == "login") {
        $email_phone = $_POST['email_phone'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Admin login check
        if (array_key_exists($email_phone, $admin_credentials)) {
            if (password_verify($confirm_password, $admin_credentials[$email_phone])) {
                header("Location: admin/admin.php?message=Welcome Admin!");
                exit();
            } else {
                header("Location: login.php?message=You are not an Admin!");
                exit();
            }
        }

        // User login check
        $sql = "SELECT * FROM users WHERE email = '$email_phone' OR phone = '$email_phone'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($confirm_password, $user['password'])) {
                header("Location: Home.php?message=Login successful!");
                exit();
            } else {
                header("Location: login.php?message=Invalid password!");
                exit();
            }
        } else {
            header("Location: login.php?message=User not found!");
            exit();
        }

    // -------------------------- NGO REGISTRATION ---------------------------- //
    } elseif ($action == "NGOReg") {
        $ngo_name = $_POST['ngo_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO ngo (ngo_name, address, email, phone, password) 
                    VALUES ('$ngo_name', '$address', '$email', '$phone', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location:NGOReg.php?message=NGO Registration successful!&redirect=NGOlog.php");
                exit();
            } else {
                header("Location: NGOReg.php?message=NGO Registration failed: " . urlencode($conn->error));
                exit();
            }
        } else {
            header("Location: NGOReg.php?message=Passwords do not match!");
            exit();
        }

    // -------------------------- NGO LOGIN ---------------------------- //
    } elseif ($action == "NGOlog") {
        $email_phone = $_POST['email_phone'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $sql = "SELECT * FROM ngo WHERE email = '$email_phone' OR phone = '$email_phone'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $ngo = $result->fetch_assoc();
            if (password_verify($confirm_password, $ngo['password'])) {
                header("Location: Home.php?message=NGO Login successful!");
                exit();
            } else {
                header("Location: NGOlog.php?message=Invalid password!");
                exit();
            }
        } else {
            header("Location: NGOlog.php?message=NGO not found!");
            exit();
        }

    } else {
        header("Location: NGOReg.php?message=Invalid action specified.");
        exit();
    }

} else {
    header("Location: NGOReg.php?message=Invalid access.");
    exit();
}

$conn->close();
?>
