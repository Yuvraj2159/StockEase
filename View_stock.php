<?php

require_once('./connection/config.php');
$sql = "SELECT id, item_name, quantity, price FROM stock_items"; // Include 'id' to identify each item

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stock</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Reuse Dashboard CSS -->
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Custom CSS for tables -->
    <script>
        function confirmDelete(itemId) {
            if (confirm("Are you sure you want to delete this item?")) {
                window.location.href = `delete_stock.php?id=${itemId}`;
            }
        }
    </script>
</head>

<body>
    <header class="dashboard-header">
        <h1>View Stock</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are rows to display
                if ($result->num_rows > 0) {
                    // Fetch rows and display them
                    while ($row = $result->fetch_assoc()) {
                        $total_amount = $row['quantity'] * $row['price'];
                        echo "<tr>
                                <td>" . htmlspecialchars($row['item_name']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>" . htmlspecialchars($row['price']) . "</td>
                                <td>" . htmlspecialchars(number_format($total_amount, 2)) . "</td>
                                <td>
                                    <a href='edit_stock.php?id=" . $row['id'] . "' class='edit-button'>Edit</a>
                                    <button onclick='confirmDelete(" . $row['id'] . ")' class='delete-button'>Delete</button>
                                </td>
                              </tr>";
                    }
                } else {
                    // Display a message if no data exists
                    echo "<tr><td colspan='5'>No stock items found.</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </main>
</body>

</html>
