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
    <form method="POST" action="welcome.php">
    
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