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
        <form method="POST" action="register.php">
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
