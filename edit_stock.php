<?php
require_once('./connection/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM stock_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $item_name = $_POST['item_name'];
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $category = $_POST['category']; 

    $sql = "UPDATE stock_items SET item_name = ?, quantity = ?, price = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidss", $item_name, $quantity, $price, $category, $id);

    if ($stmt->execute()) {
        header("Location: Inventory.php");
        exit();
    } else {
        $error = "Error updating stock item.";
    }
    $stmt->close();
} else {
    header("Location: Inventory.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css">
    <link rel="stylesheet" href="./css/Edit_stock.css">
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
        <h1>Edit Stock</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Inventory.php">Back to Inventory</a></li>
            </ul>
        </nav>
    </header>

<div class="container">
    <h2>Edit Stock</h2>

    <form method="post" action="edit_stock.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Mobile Devices" <?= ($item['category'] == "Mobile Devices") ? "selected" : "" ?>>Mobile Devices</option>
            <option value="Accessories" <?= ($item['category'] == "Accessories") ? "selected" : "" ?>>Accessories</option>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>

        <label for="price">Price per Item:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>

        <button type="submit" class="btn">Save Changes</button>
        <a href="Inventory.php" class="cancel-button">Cancel</a>
    </form>

    <?php if (isset($error)) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</div>

</body>
</html>
