<?php
// Include database configuration
include('./connection/config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (basic validation)
    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into the database
    $sql = "INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Register - STOCKEASE</title>
    <link rel="stylesheet" href="./css/Register.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="register-container">
        <h1>Register to STOCKEASE</h1>
        <form method="POST" action="Register.php">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Choose a username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
