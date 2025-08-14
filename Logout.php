<?php
// Start the session
session_start();

// Check if the session exists
if (isset($_SESSION['username'])) {
    // Unset all session variables
    session_unset();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to index.html after logout
    header("Location: index.html");
    exit();
} else {
    // If no session exists, redirect to index.html
    header("Location: index.html");
    exit();
}
?>
