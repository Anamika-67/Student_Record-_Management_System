<?php
// Start the session to manage user state
session_start();

// Check if the user is logged in
// If the session variable 'user_id' is not set, they are not authenticated
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Always exit after a header redirect to stop further execution
}
?>
