<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php?login_is_not_set");
    exit;
}

require './connection/config.php';

// Initialize message
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = "";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_name = $_POST["itemName"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Insert data into the table
    $sql = "INSERT INTO stock_items (item_name, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $item_name, $quantity, $price);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    // Redirect to refresh the page and display the message
    header("Location: Add_stock.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css">

    <style>
        .success-message {
            text-align: center;
            color: green;
            font-size: 16px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <h1>Add Stock</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <!-- Display Success Message at the Top -->
        <?php if (!empty($_SESSION['message'])): ?>
            <p class="success-message"><?php echo $_SESSION['message']; ?></p>
            <?php $_SESSION['message'] = ""; // Clear message after displaying ?>
        <?php endif; ?>

        <form class="stock-form" action="Add_stock.php" method="POST">
            <h2>Add New Stock</h2>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" placeholder="Enter item name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required>

            <label for="price">Price per Item:</label>
            <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required>

            <button type="submit" class="btn">Add Stock</button>
        </form>
    </main>
</body>
</html>
