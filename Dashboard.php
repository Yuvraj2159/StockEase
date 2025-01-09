<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STOCKEASE</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Link to CSS -->
</head>
<body>
    <header class="dashboard-header">
        <h1>Welcome to STOCKEASE Dashboard</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="Add_stock.php">Add Stock</a></li>
                <li><a href="View_stock.php">View Stock</a></li>
                <li><a href="#">Customers</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="logout.php" class="logout-button">Logout</a>
            </ul>
        </nav>
    </header>
    

    <main class="dashboard-main">
        <section class="overview-section">
            <h2>Overview</h2>
            <p>Manage your inventory efficiently with STOCKEASE. Track, update, and analyze your stock in one place.</p>
        </section>

        <section class="features-section">
            <div class="feature">
            
                <h3>Add Stock</h3>
                
                <p>Insert new stock items into your inventory with ease.</p>
                <a href="Add_stock.php" class="btn">Add Stock</a></li>
            </div>
            <div class="feature">
                <h3>View Stock</h3>
                
                <p>Check your inventory details and manage items.</p>
                <a href="View_stock.php" class="btn">View Stock</a></li>
            </div>
            <div class="feature">
                <h3>Generate Reports</h3>
                <p>Create detailed reports to analyze stock performance.</p>
                <a href="#" class="btn">Generate Report</a>
            </div>
        </section>
    </main>
</body>
</html>
