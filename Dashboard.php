<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User'; // Get user's name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STOCKEASE</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Link to CSS -->
    <style>
        .user-info {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .user-info a {
            text-decoration: none;
            color: #fff;
            transition: 0.3s;
        }
        .user-info a:hover {
            color: yellow;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <h1>Welcome to STOCKEASE Dashboard</h1>
        
        <!-- Clickable Profile Link -->
        <div class="user-info">
            👤 <a href="profile.php"><?= htmlspecialchars($full_name); ?></a>
        </div>

        <nav class="dashboard-nav">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="Add_stock.php">Add Stock</a></li>
                <li><a href="Inventory.php">Inventory</a></li>
                <li><a href="add_customer.php">Customers</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="logout.php" class="logout-button" onclick="return confirmLogout()">Logout</a></li>
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
                <a href="Add_stock.php" class="btn">Add Stock</a>
            </div>
            <div class="feature">
                <h3>Inventory</h3>
                <p>Check your inventory details and manage items.</p>
                <a href="Inventory.php" class="btn">View Stock</a>
            </div>
            <div class="feature">
                <h3>Customers</h3>
                <p>Check your customers and add customer details.</p>
                <a href="add_customer.php" class="btn">Customers</a>
            </div>
            <div class="feature">
                <h3>Generate Reports</h3>
                <p>Create detailed reports to analyze stock performance.</p>
                <a href="Generate_report.php" class="btn">Generate Report</a>
            </div>
        </section>
    </main>

    <script>
        // Confirm logout
        function confirmLogout() {
            return confirm("Are you sure you want to logout?");
        }
    </script>
</body>
</html>
