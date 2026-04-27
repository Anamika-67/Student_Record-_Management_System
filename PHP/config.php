<?php
// Database Configuration
$host = 'localhost';
$dbname = 'student_management';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    // Create a PDO instance (PHP Data Objects)
    // PDO is preferred over mysqli for its flexibility and better security features (Prepared Statements)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception for easier debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // If connection fails, display an error message
    // In a production environment, you should log this error instead of showing it to the user
    die("Database Connection Failed: " . $e->getMessage());
}
?>
