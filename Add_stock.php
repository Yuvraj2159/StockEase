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
if (!isset($_SESSION['messageClass'])) {
    $_SESSION['messageClass'] = "";
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
        $_SESSION['message'] = "✅ Item Added Successfully!";
        $_SESSION['messageClass'] = "success";
    } else {
        $_SESSION['message'] = "❌ Error: " . $stmt->error;
        $_SESSION['messageClass'] = "error";
    }
    $stmt->close();

    // Redirect to the same page to display the message
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
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Custom CSS for forms -->

    <style>
        .message-box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
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

        <!-- Display Persistent Message Below Form -->
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="message-box <?php echo $_SESSION['messageClass']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php 
            // Clear message after displaying it once
            $_SESSION['message'] = ""; 
            $_SESSION['messageClass'] = "";
            ?>
        <?php endif; ?>
    </main>
</body>
</html>
