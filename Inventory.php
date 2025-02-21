<?php
// Include your database connection file
require_once('./connection/config.php');

// Fetch stock items
$sql = "SELECT id, item_name, quantity, price FROM stock_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/Stock.css"> <!-- Custom CSS -->
    <style>
        /* Flexbox Layout */
        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }

        /* Stock Section */
        .stock {
            flex: 1;
            background: #f0f4f8;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Cart Section */
        .cart {
            flex: 0.5;
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            margin: 2px;
        }

        .edit-button { background: black; color: white; }
        .delete-button { background: #dc3545; color: white; }
        .add-to-cart { background: #28a745; color: white; }

        .checkout {
            background: #ff6600;
            color: white;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header class="dashboard-header">
        <h1>Inventory</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <!-- Stock & Cart Section -->
    <div class="container">

        <!-- Stock Section -->
        <div class="stock">
            <h2 style="text-align: center;">Stock</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="stock-items">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr data-id='" . $row['id'] . "' data-name='" . htmlspecialchars($row['item_name']) . "' data-price='" . $row['price'] . "'>
                                    <td>" . htmlspecialchars($row['item_name']) . "</td>
                                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                                    <td>₹" . number_format($row['price'], 2) . "</td>
                                    <td>
                                        <a href='edit_stock.php?id=" . $row['id'] . "' class='edit-button'>Edit</a>
                                        <button class='delete-button' onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                                        <button class='add-to-cart'>Add to Cart</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No stock items available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Cart Section -->
        <div class="cart">
            <h2 style="text-align: center; background-color: black; color: white;">Cart</h2>
            <div id="cart-items">
                <p>Your cart is empty</p>
            </div>
            <div class="cart-summary">
                <p id="item-total">Item Total: ₹0.00</p>
                <p id="grand-total"><strong>Grand Total: ₹0.00</strong></p>
            </div>
            <button class="checkout" id="checkout-button">Checkout</button>
        </div>

    </div>

    <script>
        const cart = {};

        // Add item to cart
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', event => {
                const row = event.target.closest('tr');
                const itemId = row.getAttribute('data-id');
                const itemName = row.getAttribute('data-name');
                const itemPrice = parseFloat(row.getAttribute('data-price'));

                if (!cart[itemId]) {
                    cart[itemId] = { name: itemName, price: itemPrice, quantity: 1 };
                } else {
                    cart[itemId].quantity++;
                }

                updateCart();
            });
        });

        // Update cart
        function updateCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = '';
            let total = 0;

            for (const [id, item] of Object.entries(cart)) {
                total += item.price * item.quantity;
                cartItemsContainer.innerHTML += `
                    <div class="cart-item" data-id="${id}">
                        <span>${item.name}</span>
                        <div>
                            <button onclick="changeQuantity('${id}', -1)">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="changeQuantity('${id}', 1)">+</button>
                            <button class="remove" onclick="removeFromCart('${id}')">Remove</button>
                        </div>
                        <span>₹${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `;
            }

            if (Object.keys(cart).length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
            }

            document.getElementById('item-total').innerText = `Item Total: ₹${total.toFixed(2)}`;
            document.getElementById('grand-total').innerText = `Grand Total: ₹${total.toFixed(2)}`;
        }

        // Change quantity
        function changeQuantity(itemId, delta) {
            if (cart[itemId]) {
                cart[itemId].quantity += delta;
                if (cart[itemId].quantity <= 0) {
                    delete cart[itemId];
                }
                updateCart();
            }
        }

        // Remove item from cart
        function removeFromCart(itemId) {
            delete cart[itemId];
            updateCart();
        }

        // Confirm delete action
        function confirmDelete(itemId) {
            if (confirm("Are you sure you want to delete this item?")) {
                window.location.href = `delete_stock.php?id=${itemId}`;
            }
        }
        
        // Checkout
        document.getElementById('checkout-button').addEventListener('click', function () {
            if (Object.keys(cart).length === 0) {
                alert("Your cart is empty!");
                return;
            }

            // Send cart data to checkout.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'checkout.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cartData';
            input.value = JSON.stringify(cart);
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });

    </script>

</body>
</html>
