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

    //     foreach ($cart as $id => $item) {
//         $sql = "SELECT id,quantity FROM stock_items WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("i", $id);
//         $stmt->execute();
//         $stmt->bind_result($stockQuantity);
//         $stmt->fetch();
//         $stmt->close();

    //         if ($stockQuantity < $item['quantity']) {
//             $error = true;
//             break;
//         }

    //         $newQuantity = $stockQuantity - $item['quantity'];
//         $updateSql = "UPDATE stock_items SET quantity = ? WHERE id = ?";
//         $updateStmt = $conn->prepare($updateSql);
//         $updateStmt->bind_param("ii", $newQuantity, $id);
//         $updateStmt->execute();
//         $updateStmt->close();
//     }

    if ($error) {
        $conn->rollback();
        echo "<p>Transaction failed: Not enough stock!</p>";
        exit;
    } else {
        $conn->commit();
    }
}
// Get customer ID from URL
$customerId = isset($_GET['customer']) ? (int) $_GET['customer'] : 0;

// Fetch customer details securely using a prepared statement
$sql = "SELECT cus_name, email, phone FROM customers WHERE cus_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
}
$stmt->close();
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
<style>
    .box {
        width: 100%px;
        border: 2px solid black;
        padding: 10px;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        border: 1px solid black;
        padding: 5px;
    }
</style>

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
    <div class="box">
        <h3>Customer Details</h3>
        <?php if ($customer): ?>
            <table>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($customer["cus_name"]); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($customer["email"]); ?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><?php echo htmlspecialchars($customer["phone"]); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No customer found.</p>
        <?php endif; ?>
    </div>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php
        $grandTotal = 0; ?>
        <table>
            <?php foreach ($cart as $id => $item): ?>
                <?php
                $total = $item['price'] * $item['quantity'];
                $grandTotal += $total;
                ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td>₹<?= number_format($total, 2) ?></td>
                </tr>

            <?php endforeach; ?>

            <!-- Display the grand total -->
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td><strong>₹<?= number_format($grandTotal, 2) ?></strong></td>
            </tr>
        </table>

        <!-- Form to submit the cart data to bill.php -->
        <form action="bill.php" method="post">
            <?php foreach ($cart as $index => $item): ?>
                <input type="hidden" name="cart[<?= $index ?>][name]" value="<?= htmlspecialchars($item['name']) ?>">
                <input type="hidden" name="cart[<?= $index ?>][quantity]"
                    value="<?= htmlspecialchars($item['quantity']) ?>">
                <input type="hidden" name="cart[<?= $index ?>][price]" value="<?= htmlspecialchars($item['price']) ?>">
            <?php endforeach; ?>
            <input type="hidden" name="cus_id" value="<?php echo $cust_id = $_GET['customer'] ?>">
            <button type="submit" name="update_stock" onclick="printBill()">Proceed to Final Bill</button>
        </form>

        <script>
            function printBill() {
                window.print();
            }
        </script>
</body>

</html>