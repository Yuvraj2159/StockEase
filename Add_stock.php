<!-- <?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php?login_is_not_set");
    // header("Location: login.php"); // Redirect to login if not logged in
    exit;
}


require './connection/config.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_name = $_POST["itemName"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Insert data into the table
    $sql = "INSERT INTO stock_items (item_name, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $item_name, $quantity, $price);

    if ($stmt->execute()) {
        echo "<h1> Item added </h1>";
        echo "Stock item added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Reuse Dashboard CSS -->
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Custom CSS for forms -->
</head>
<body>
    <header class="dashboard-header">
        <h1>Add Stock</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <form class="stock-form" action="Add_stock.php" method="POST">
            <h2>Add New Stock</h2>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" placeholder="Enter item name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required>

            <label for="price">Price per Item:</label>
            <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required>

            <button type="submit" class="btn">Add Stock</button>
        </form>
    </main>
</body>
</html>