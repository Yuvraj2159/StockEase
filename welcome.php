
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional styling -->
</head>
<body>
    <div class="welcome-container">
        <h1>
            Welcome, 
            <?php 
            echo isset($_POST["Pawan"]) ? htmlspecialchars($_POST["Pawan"]) : "Guest"; 
            ?>!
        </h1>
        <p>You have successfully logged in.</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
    
