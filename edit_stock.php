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

    $sql = "UPDATE stock_items SET item_name = ?, quantity = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $item_name, $quantity, $price, $id);

    if ($stmt->execute()) {
        header("Location: view_stock.php");
        exit();
    } else {
        $error = "Error updating stock item.";
    }
    $stmt->close();
} else {
    header("Location: view_stock.php");
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
    
    <link rel="stylesheet" href="./css/Edit stock.css"> <!-- Link to the CSS file -->
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

    <main class="dashboard-main">
        <form method="post" action="edit_stock.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
            <div>
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
            </div>
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>
            </div>
            <div>
                <button type="submit">Save Changes</button>
                <a href="view_stock.php" class="cancel-button">Cancel</a>
            </div>
        </form>
        <?php if (isset($error)) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </main>
</body>

</html>
