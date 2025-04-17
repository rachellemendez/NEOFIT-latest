<?php
session_start();

// Destroy the session
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page or any other page you want after logging out
header("Location: login.php");
exit();
?>
