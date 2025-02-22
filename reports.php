<?php
include 'connection/config.php';

// Get selected date and time range or set default (last 30 days, full-day time range)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'last_30_days';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

switch ($filter) {
    case 'today':
        $start_date = $end_date = date('Y-m-d');
        break;
    case 'yesterday':
        $start_date = $end_date = date('Y-m-d', strtotime('-1 day'));
        break;
    case 'past_week': // Previous full week (Monday to Sunday)
        $start_date = date('Y-m-d', strtotime('monday last week'));
        $end_date = date('Y-m-d', strtotime('sunday last week'));
        break;
    case 'this_week':
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d');
        break;
    default:
        if (empty($start_date) || empty($end_date)) {
            $start_date = date('Y-m-d', strtotime('-30 days'));
            $end_date = date('Y-m-d');
        }
}

$start_time = '00:00:00';
$end_time = '23:59:59';

// Fetch filtered sales data
$sql = "SELECT sh.id, c.cus_name, c.email, c.phone, sh.item_name, sh.quantity, sh.price, sh.total, sh.sale_date
        FROM sales_history sh
        JOIN customers c ON sh.cus_id = c.cus_id
        WHERE sh.sale_date BETWEEN ? AND ?
        ORDER BY sh.sale_date ASC";

$start_datetime = "$start_date $start_time";
$end_datetime = "$end_date $end_time";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start_datetime, $end_datetime);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
        }
        .dashboard-header {
            background-color: #005f73;
            color: white;
            padding: 15px;
            font-size: 24px;
        }
        .dashboard-nav {
            margin-top: 10px;
        }
        .dashboard-nav a {
            text-decoration: none;
            color: white;
            background-color: #0a9396;
            padding: 10px 20px;
            border-radius: 5px;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #94d2bd;
        }
        .filter-form {
            margin-top: 20px;
        }
    </style>
    <meta charset="UTF-8">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css">
    <link rel="stylesheet" href="./css/reports.css">
</head>
<body>
<header class="dashboard-header">
    <h1>Sales Reports</h1>
    <nav class="dashboard-nav">
        <ul>
            <li><a href="Dashboard.php">Back to Dashboard</a></li>
        </ul>
    </nav>
</header>

<!-- Date and Preset Filter Form -->
<form method="GET" action="">
    <label for="filter">Quick Filter:</label>
    <select name="filter" onchange="this.form.submit()">
        <option value="last_30_days" <?= ($filter == 'last_30_days') ? 'selected' : ''; ?>>Last 30 Days</option>
        <option value="today" <?= ($filter == 'today') ? 'selected' : ''; ?>>Today</option>
        <option value="yesterday" <?= ($filter == 'yesterday') ? 'selected' : ''; ?>>Yesterday</option>
        <option value="past_week" <?= ($filter == 'past_week') ? 'selected' : ''; ?>>Past Week</option>
        <option value="this_week" <?= ($filter == 'this_week') ? 'selected' : ''; ?>>This Week</option>
    </select>
    
    <br><br>
    
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" value="<?= $start_date; ?>">
    
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" value="<?= $end_date; ?>">
    
    <button type="submit">Filter</button>
</form>

<br>

<?php if ($result->num_rows > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
                <th>Total (₹)</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['cus_name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['item_name']); ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td>₹<?= number_format($row['price'], 2); ?></td>
                    <td><strong>₹<?= number_format($row['total'], 2); ?></strong></td>
                    <td><?= $row['sale_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No sales records found for the selected date range.</p>
<?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
