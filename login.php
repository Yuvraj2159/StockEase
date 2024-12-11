<?php
// Include database configuration
include('./connection/config.php');

// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (basic validation)
    if (empty($username) || empty($password)) {
        echo "Username and Password are required!";
        exit;
    }

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect to the dashboard
            header("Location: Dashboard.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="welcome-text">Welcome to STOCKEASE</div>
    <form method="POST" action="login.php">
    
        <label for="username">Username:</label>
        <input type="text" name="username" id="Name" required> 

        <label for="password">Password:</label>
        <input type="password" name="password" id="12345" required>

        <button type="submit">Login</button>
        <p class="register-link">
            Don't have an account? <a href="Register.php">Register here</a>.
            
        </p>
    </form>
    
</body>
</html>