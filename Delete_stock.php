<?php

require_once('./connection/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input
    $sql = "DELETE FROM stock_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to View Stock page after deletion
        header("Location: Inventory.php?message=Item deleted successfully");
        exit();
    } else {
        echo "Error deleting item: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: Inventory.php");
    exit();
}

$conn->close();
?>
