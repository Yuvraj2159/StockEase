<?php
// connection/config.php
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
    <title>Stock Management with Cart</title>
    <link rel="stylesheet" href="./css/Dashboard.css">
    <link rel="stylesheet" href="./css/inventory.css">
    

    <link rel="stylesheet" href="./css/Stock.css">
</head>

<body>
<header class="dashboard-header">
        <h1>Inventory</h1>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="Dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
</header>
    <!-- Stock Section -->
    <div class="Stock">
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
            <tbody id="Stock-items">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . $row['id'] . "' data-name='" . htmlspecialchars($row['item_name']) . "' data-price='" . $row['price'] . "'>
                                <td>" . htmlspecialchars($row['item_name']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>₹" . htmlspecialchars($row['price']) . "</td>
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
    <h2 style="text-align: center;background-color: black; color: white;">Cart</h2>
        <div id="cart-items">
            <p>Your cart is empty</p>
        </div>
        <div class="cart-summary">
            <p id="item-total">Item Total: ₹0.00</p>
            <p id="grand-total"><strong>Grand Total: ₹0.00</strong></p>
        </div>
        <button class="checkout" id="checkout-button">Checkout</button>

<script>
    document.getElementById('checkout-button').addEventListener('click', function () {
        if (Object.keys(cart).length === 0) {
            alert("Your cart is empty!");
            return;
        }

        // Send cart data to checkout.php using a form
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
            document.getElementById('grand-total').innerText = `Grand Total: ₹${(total).toFixed(2)}`;
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
        
    </script>
</body>

</html>