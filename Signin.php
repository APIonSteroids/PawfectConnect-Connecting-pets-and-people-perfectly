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
    $password = trim($_POST['password']);

    // Validate that both fields are not empty
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'signin.html'; // Fixed the spelling of 'signin'
              </script>";
        exit();
    }

    // Prepare the SQL query to check if the user exists
    $checkUserQuery = "SELECT * FROM users_data WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("ss", $username, $password); // Bind the username and password parameters
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching user was found
    if ($result->num_rows > 0) {
        // User found, redirect to the dashboard
        header("Location: dashboard.html");
        exit();
    } else {
        // Invalid username or password
        echo "<script>
                alert('Invalid username or password!');
                window.location.href = 'signin.html'; // Fixed the spelling of 'signin'
              </script>";
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If accessed without a POST request, redirect to the signin page
    header("Location: singin.html"); // Fixed the spelling of 'signin'
    exit();
}
?>
