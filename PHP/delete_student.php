<?php
require_once 'config.php';
require_once 'auth.php';

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Use prepared statements for deletion to prevent SQL Injection
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: index.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting record.";
        }
    } catch(PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    // Redirect if no ID is passed
    header("Location: index.php");
    exit();
}
?>
