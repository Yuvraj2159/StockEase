<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['sale_id'])) {
    echo "Invalid sale ID.";
    exit();
}

$sale_id = $_GET['sale_id'];
$sql = "SELECT * FROM sales WHERE id = $sale_id";
$result = $conn->query($sql);
$sale = $result->fetch_assoc();

$sql = "SELECT i.name, s.quantity, s.price FROM sale_items s 
        JOIN inventory i ON s.item_id = i.id WHERE s.sale_id = $sale_id";
$sale_items = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bill</title>
    <script>
        function printBill() {
            window.print();
        }
    </script>
</head>
<body>
    <h2>Sales Bill</h2>
    <p>Sale ID: <?php echo $sale_id; ?></p>
    <p>Date: <?php echo $sale['sale_date']; ?></p>
    <table border="1">
        <tr><th>Item</th><th>Quantity</th><th>Price</th></tr>
        <?php while ($row = $sale_items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <h3>Total Price: ₹<?php echo $sale['total_price']; ?></h3>
    <button onclick="printBill()">Print Bill</button>
</body>
</html>
