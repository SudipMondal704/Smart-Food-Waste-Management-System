<?php
session_start();
session_unset();
session_destroy();
header("Location: ../login_signup.php?message=Logout Successfully!"); // Change path if needed
exit();
?>
