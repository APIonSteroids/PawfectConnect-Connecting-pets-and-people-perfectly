<?php
// Database connection details
$servername = "sql102.infinityfree.com";
$db_username = "if0_38182063";
$db_password = "Jeevan020602";
$dbname = "if0_38182063_petdb";

// Create a database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

    // Validate that passwords match
    if ($password !== $confirmPassword) {
        echo "<h3 style='color:red;'>Passwords do not match!</h3>";
        header("Refresh: 3; URL=signup.html"); // Redirect back to signup page
        exit();
    }

    // Check if the username or email already exists
    $checkUserQuery = "SELECT * FROM users_data WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect with an error message if user exists
        echo "<h3 style='color:red;'>Username or Email already exists!</h3>";
        header("Refresh: 3; URL=signup.html");
        exit();
    }

    // Insert new user data into the database (storing plain text password)
    $insertQuery = "INSERT INTO users_data (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Redirect to the login page with a success message
        header("Location: singin.html?message=Registration successful!");
        exit();
    } else {
        // Display an error alert and redirect back to the signup page
        echo "<script>
                alert('Error in registration. Please try again.');
                window.location.href = 'singup.html';
              </script>";
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If accessed without a POST request, redirect to the signup page
    header("Location: singup.html");
    exit();
}
?>
