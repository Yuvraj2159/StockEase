<?php
include 'connection/config.php';
session_start();

$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart']) && is_array($_POST['cart']) && isset($_POST['cus_id'])) {
    $cart = $_POST['cart'];
    $customer_id = (int) $_POST['cus_id'];

    $conn->begin_transaction(); // Start Transaction
    $error = false;

    foreach ($cart as $item) {
        if (!isset($item['name']) || !isset($item['quantity']) || !isset($item['price'])) {
            echo "<p style='color:red;'>Invalid item data.</p>";
            $error = true;
            break;
        }

        $name = $conn->real_escape_string($item['name']);
        $quantity = (int) $item['quantity'];
        $price = (float) $item['price'];
        $total = $quantity * $price;

        // Check stock availability
        $check_stock = "SELECT quantity FROM stock_items WHERE item_name = ?";
        $stmt = $conn->prepare($check_stock);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $available_quantity = (int) $row['quantity'];

            if ($available_quantity >= $quantity) {
                // Deduct stock quantity
                $update_stock = "UPDATE stock_items SET quantity = quantity - ? WHERE item_name = ?";
                $stmt = $conn->prepare($update_stock);
                $stmt->bind_param("is", $quantity, $name);
                if (!$stmt->execute()) {
                    $error = true;
                    break;
                }

                // Insert sale record into `sales_history`
                $insert_sale = "INSERT INTO sales_history (cus_id, item_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sale);
                $stmt->bind_param("isidd", $customer_id, $name, $quantity, $price, $total);
                if (!$stmt->execute()) {
                    $error = true;
                    break;
                }
            } else {
                echo "<p style='color:red;'>Not enough stock for $name (Available: $available_quantity, Requested: $quantity)</p>";
                $error = true;
                break;
            }
        } else {
            echo "<p style='color:red;'>Item $name not found in stock.</p>";
            $error = true;
            break;
        }
    }

    if ($error) {
        $conn->rollback();
        echo "<p style='color:red;'>Transaction failed! Please try again.</p>";
    } else {
        $conn->commit();
        // Redirect to dashboard with a success message
        header("Location: Dashboard.php?success=true");
        exit();
    }
} else {
    echo "<p style='color:red;'>Invalid request!</p>";
}
?>
