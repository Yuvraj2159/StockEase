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
    $item_name = trim($_POST["itemName"]);
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $category = $_POST["category"]; 

    // Insert data into the table
    $sql = "INSERT INTO stock_items (item_name, quantity, price, category) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sids", $item_name, $quantity, $price, $category);

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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        
        .container {
            width: 40%;
            background: white;
            padding: 25px;
            margin: auto;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .success-message {
            color: green;
            font-size: 16px;
            margin-bottom: 15px;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            text-align: left;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            background: linear-gradient(to right, #4CAF50, #00796B);
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            margin-top: 15px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
        }

        .btn:hover {
            background: linear-gradient(to right, #45a049, #004d40);
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

<div class="container">
    <h2>Add New Stock</h2>

    <!-- Display Success Message -->
    <?php if (!empty($_SESSION['message'])): ?>
        <p class="success-message"><?php echo $_SESSION['message']; ?></p>
        <?php $_SESSION['message'] = ""; // Clear message after displaying ?>
    <?php endif; ?>

    <form action="Add_stock.php" method="POST">
        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" placeholder="Enter item name" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Mobile Devices">Mobile Devices</option>
            <option value="Accessories">Accessories</option>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" placeholder="Enter quantity" required>

        <label for="price">Price per Item:</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" placeholder="Enter price" required>

        <button type="submit" class="btn">Add Stock</button>
    </form>
</div>

</body>
</html>
