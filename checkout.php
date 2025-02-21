<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty</h2>";
    exit();
}

// Function to deduct stock after a successful sale
function updateInventory($conn, $cart) {
    foreach ($cart as $item_id => $item) {
        $quantity = $item['quantity'];
        $sql = "UPDATE inventory SET quantity = quantity - $quantity WHERE id = $item_id";
        $conn->query($sql);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_sale'])) {
    // Save transaction details to sales table
    $total_price = $_POST['total_price'];
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO sales (total_price, sale_date) VALUES ($total_price, '$date')";
    if ($conn->query($sql) === TRUE) {
        $sale_id = $conn->insert_id;
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $sql = "INSERT INTO sale_items (sale_id, item_id, quantity, price) VALUES ($sale_id, $item_id, ".$item['quantity'].", ".$item['price'].")";
            $conn->query($sql);
        }
        updateInventory($conn, $_SESSION['cart']);
        $_SESSION['cart'] = []; // Clear the cart after sale
        header("Location: bill.php?sale_id=$sale_id"); // Redirect to bill generation page
        exit();
    } else {
        echo "Error processing sale.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <table border="1">
        <tr><th>Item</th><th>Quantity</th><th>Price</th></tr>
        <?php
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr><td>{$item['name']}</td><td>{$item['quantity']}</td><td>₹{$item['price']}</td></tr>";
            $total_price += $item['quantity'] * $item['price'];
        }
        ?>
    </table>
    <h3>Total: ₹<?php echo $total_price; ?></h3>
    <form method="post">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <button type="submit" name="confirm_sale">Confirm Sale</button>
    </form>
</body>
</html>
