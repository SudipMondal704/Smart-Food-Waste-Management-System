<?php
session_start();
session_unset();
session_destroy();
header("Location: ../Login.php?message=Logout Successfully!"); // Change path if needed
exit();
?>
