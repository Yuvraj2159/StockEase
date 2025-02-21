<?php
require_once('./connection/config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cartData'])) {
    $cart = json_decode($_POST['cartData'], true);
    
    if (!$cart) {
        echo "Invalid cart data!";
        exit;
    }

    $error = false;
    $conn->begin_transaction();

    foreach ($cart as $id => $item) {
        $sql = "SELECT quantity FROM stock_items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($stockQuantity);
        $stmt->fetch();
        $stmt->close();

        if ($stockQuantity < $item['quantity']) {
            $error = true;
            break;
        }
        
        $newQuantity = $stockQuantity - $item['quantity'];
        $updateSql = "UPDATE stock_items SET quantity = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $newQuantity, $id);
        $updateStmt->execute();
        $updateStmt->close();
    }

    if ($error) {
        $conn->rollback();
        echo "<p>Transaction failed: Not enough stock!</p>";
        exit;
    } else {
        $conn->commit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="./css/checkout.css">
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css">
</head>
<body>
<header class="dashboard-header">
        <h1>Checkout</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Inventory.php">Back to Inventory</a></li>
            </ul>
        </nav>
    </header>
    <h2>Order Summary</h2>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php
        $grandTotal = 0;
        foreach ($cart as $id => $item) {
            $total = $item['price'] * $item['quantity'];
            $grandTotal += $total;
            echo "<tr>
                    <td>{$item['name']}</td>
                    <td>{$item['quantity']}</td>
                    <td>₹{$item['price']}</td>
                    <td>₹{$total}</td>
                  </tr>";
        }
        ?>
        <tr>
            <td colspan="3"><strong>Grand Total</strong></td>
            <td><strong>₹<?php echo number_format($grandTotal, 2); ?></strong></td>
        </tr>
    </table>

    <button onclick="printBill()">Print Bill</button>

    <script>
        function printBill() {
            window.print();
        }
    </script>
</body>
</html>