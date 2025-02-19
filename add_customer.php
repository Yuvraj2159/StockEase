<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to dashboard or login if not logged in
    header("Location: dashboard.php?login_is_not_set");
    exit;
}

require './connection/config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cus_name = trim($_POST["cus_name"]);
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);

    // Basic validation
    if (!empty($cus_name) && !empty($email) && !empty($phone)) {
        
        // Check if phone is exactly 10 digits
        if (strlen($phone) !== 10) {
            // You could also check if it's all digits, e.g.:
            // if (!preg_match('/^[0-9]{10}$/', $phone)) { ... }
            $error = "Please enter a valid 10-digit phone number.";
        } else {
            // 1. Check if a user with the same email or phone already exists
            $checkSql = "SELECT COUNT(*) AS cnt FROM customers WHERE email = ? OR phone = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ss", $email, $phone);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['cnt'];
            $checkStmt->close();

            if ($count > 0) {
                // If record exists, show error
                $error = "A customer with this email or phone number already exists.";
            } else {
                // Otherwise, insert new record
                $sql  = "INSERT INTO customers (cus_name, email, phone) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $cus_name, $email, $phone);

                if ($stmt->execute()) {
                    $message = "Customer added successfully!";
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    } else {
        $error = "Please fill out all fields.";
    }
}

// Retrieve all customers (latest first)
$sql    = "SELECT * FROM customers ORDER BY cus_id ASC";
$result = $conn->query($sql);

$customers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - STOCKEASE</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Reuse form styles if needed -->
    <style>
        /* Optional additional styling */
        .message { color: green; }
        .error { color: red; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <h1>Customers</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <!-- Display success or error messages -->
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Form to add a new customer -->
        <form class="stock-form" action="add_customer.php" method="POST">
            <h2>Add New Customer</h2>
            <label for="cus_name">Name:</label>
            <input type="text" id="cus_name" name="cus_name" placeholder="Enter customer's name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter customer's email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter phone number" required>

            <button type="submit" class="btn">Add Customer</button>
        </form>

        <!-- Table to display the list of customers -->
        <h2>Customer List</h2>
        <?php if (!empty($customers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['cus_id']); ?></td>
                            <td><?php echo htmlspecialchars($customer['cus_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No customers found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
