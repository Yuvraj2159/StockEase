<?php

include 'connection/config.php';
session_start();
$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];




// if (isset($_POST['update_stock']) && isset($_POST['cart'])) {
//     $cart = $_POST['cart']; // Get the cart data
//     print_r($cart); // Debugging, you can process it further
// }
// if (!isset($_GET['sale_id'])) {
//     echo "Invalid sale ID.";
//     exit();
// }

// $sale_id = $_GET['sale_id'];
// $sql = "SELECT * FROM sales WHERE id = $sale_id";
// $result = $conn->query($sql);
// $sale = $result->fetch_assoc();

// $sql = "SELECT i.name,s.sale_id, s.quantity, s.price FROM sale_items s 
//         JOIN inventory i ON s.item_id = i.id WHERE s.sale_id = $sale_id";
// $sale_items = $conn->query($sql);



// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart']) && is_array($_POST['cart'])) {
    $cart = $_POST['cart'];

    foreach ($cart as $item) {
        // Validate the required fields
        if (!isset($item['name']) || !isset($item['quantity']) || !isset($item['price'])) {
            echo "<p style='color:red;'>Invalid data for item.</p>";
            continue;
        }

        $name = $conn->real_escape_string($item['name']);
        $quantity = (int) $item['quantity'];

        // Check if the item exists and has enough stock
        $check_stock = "SELECT quantity FROM stock_items WHERE item_name = '$name'";
        $result = $conn->query($check_stock);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $available_quantity = (int) $row['quantity'];

            if ($available_quantity >= $quantity) {
                // Update stock quantity
                $update_sql = "UPDATE stock_items SET quantity = quantity - $quantity WHERE item_name = '$name'";

                if ($conn->query($update_sql) === TRUE) {
                    header("Location: Dashboard.php");
                    echo "<p style='color:green;'>Stock updated for $name (Reduced by $quantity)</p>";
                } else {
                    echo "<p style='color:red;'>Error updating stock for $name: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color:red;'>Not enough stock for $name (Available: $available_quantity, Requested: $quantity)</p>";
            }
        } else {
            echo "<p style='color:red;'>Item $name not found in stock.</p>";
        }
    }
} else {
    echo "<p style='color:red;'>No valid cart data received!</p>";
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>Bill</title>
    <script>
        function printBill() {
            window.print();
            <link rel="stylesheet" href="./css/Bill.css">
        }
    </script>
</head>

<body>

    <!-- ?>
    <form action="bill.php?" method="post ">
        <input type="hidden" name="id" value="<?php echo $row['sale_id'] ?>">
        <input type="hidden" name="quantity" value="<?php echo $row['quantity']; ?>"> >
        <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
        <button type="submit" name="update_stock" onclick="printBill()">Print Bill</button>
    </form> -->
</body>

</html>