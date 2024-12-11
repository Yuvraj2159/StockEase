<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stock</title>
    <link rel="stylesheet" href="./css/Dashboard.css"> <!-- Reuse Dashboard CSS -->
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Custom CSS for tables -->
</head>
<body>
    <header class="dashboard-header">
        <h1>View Stock</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="dashboard.html">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Example Item 1</td>
                    <td>50</td>
                    <td>$20</td>
                    <td>$1000</td>
                </tr>
                <tr>
                    <td>Example Item 2</td>
                    <td>100</td>
                    <td>$15</td>
                    <td>$1500</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
