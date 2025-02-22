<?php
include './connection/config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}


$customers = [];
$searchQuery = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchQuery = trim($_POST['search']);

    if (!empty($searchQuery)) {
        $searchQuery = $conn->real_escape_string($searchQuery);
        $sql = "SELECT * FROM customers WHERE cus_name LIKE '%$searchQuery%'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STOCKEASE</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Link to CSS -->
</head>
<style>
    .dashboard-main {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .search-box {
        padding: 10px;
        width: 100%;
        max-width: 300px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .button:hover {
        background-color: #0056b3;
    }

    .customer-list {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 5px;
        display:
            <?= (!empty($customers) || $_SERVER["REQUEST_METHOD"] == "POST") ? 'block' : 'none'; ?>
        ;
    }

    .customer-card {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .customer-card:last-child {
        border-bottom: none;
    }

    .customer-card:hover {
        background-color: #f0f0f0;
    }
</style>


<body>
    <header class="dashboard-header">
        <h1>Sales</h1>
        <nav class="dashboard-nav">
            <ul>
            <li><a href="Dashboard.php">Back to Dashboard</a></li>
                
            </ul>
        </nav>
    </header>
    <div class="dashboard-main">
        <h2>Search Customer</h2>
        <form method="POST" action="" class="search-container">
            <input type="text" name="search" class="search-box" placeholder="Enter customer name..."
                value="<?= htmlspecialchars($searchQuery) ?>" required>
            <button type="submit" class="button">Search</button>
            <button type="button" class="button" onclick="window.location.href='add_customer.php'">+ Add New
                Customer</button>
        </form>

        <div class="customer-list">
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <div class="customer-card">
                        <p><strong><?= htmlspecialchars($customer['cus_name']) ?></strong></p>
                        <p>Email: <?= htmlspecialchars($customer['email']) ?></p>
                        <p>Phone: <?= htmlspecialchars($customer['phone']) ?></p>
                        <a href="Cus_items_for_sales.php?customer=<?= $customer['cus_id'] ?>" class="button">Add Product</a>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <p>No customer found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Confirm logout
        function confirmLogout() {
            return confirm("Are you sure you want to logout?");
        }
    </script>
</body>

</html>