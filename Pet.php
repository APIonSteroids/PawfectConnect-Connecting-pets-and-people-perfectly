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
    $petName = htmlspecialchars(trim($_POST['petName']));
    $ownerName = htmlspecialchars(trim($_POST['ownerName']));
    $petType = htmlspecialchars(trim($_POST['petType']));
    $age = htmlspecialchars(trim($_POST['age']));
    $healthIssue = htmlspecialchars(trim($_POST['healthIssue']));

    // Validate required fields
    if (empty($petName) || empty($ownerName) || empty($petType) || empty($age) || empty($healthIssue)) {
        echo "<h3 style='color:red;'>All fields are required!</h3>";
        header("Refresh: 3; URL=dashboard.html"); // Redirect back to dashboard page
        exit();
    }

    // Prepare the insert query with placeholders
    $insertQuery = "INSERT INTO pets (pet_name, owner_name, pet_type, age, health_issue) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insertQuery)) {
        // Bind the parameters: 's' for string, 'i' for integer (age)
        if ($stmt->bind_param("sssis", $petName, $ownerName, $petType, $age, $healthIssue)) {
            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                // Redirect to dashboard with a success message
                header("Location: dashboard.html?message=Pet added successfully!");
                exit();
            } else {
                // Display an error message and redirect back to the dashboard
                echo "<script>
                        alert('Failed to add pet. Please try again.');
                        window.location.href = 'dashboard.html';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Failed to bind parameters. Please try again.');
                    window.location.href = 'dashboard.html';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Failed to prepare statement. Please try again.');
                window.location.href = 'dashboard.html';
              </script>";
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If accessed without a POST request, redirect to the dashboard
    header("Location: dashboard.html");
    exit();
}
?>
