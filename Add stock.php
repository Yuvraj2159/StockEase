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
                <li><a href="dashboard.html">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <form class="stock-form" action="submit-stock.php" method="POST">
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
