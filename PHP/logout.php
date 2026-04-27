<?php
// Destroy all session data
session_start();
$_SESSION = array(); // Clear all session variables
session_destroy(); // Destroy the session

// Redirect back to login page
header("Location: login.php");
exit();
?>
