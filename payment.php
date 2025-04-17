<?php
include 'user_settings_backend.php';

// Form input values
$product_name = $_POST['product_name'] ?? 'Unknown Product';
$product_price = isset($_POST['price']) ? $_POST['price'] : '0.00';
$selected_size = $_POST['size'] ?? 'Not Selected';
$selected_color = $_POST['color'] ?? 'Not Selected';
$selected_quantity = $_POST['quantity'] ?? '1';
$total_price = number_format((float)$product_price * (int)$selected_quantity, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Page</title>
</head>
<body>
    <h2>Checkout Form</h2>

    <form action="add_order.php" method="POST">
        <label for="payment-method">Choose Payment Method:</label><br>
        <select name="payment_method" id="payment-method" required>
            <option value="NeoCreds">NeoCreds</option>
            <option value="Cash On Delivery">Cash On Delivery</option>
            <option value="Pick Up">Pick Up</option>
        </select>

        <h3>Delivery Address: <?= htmlspecialchars($address); ?></h3>
        <h3>Contact Number: <?= htmlspecialchars($contact); ?></h3>

        <h3>Order Summary</h3>
        <p><strong>Product:</strong> <?= htmlspecialchars($product_name); ?></p>
        <p><strong>Price:</strong> ₱<?= htmlspecialchars($product_price); ?></p>
        <p><strong>Size:</strong> <?= htmlspecialchars($selected_size); ?></p>
        <p><strong>Color:</strong> <?= htmlspecialchars($selected_color); ?></p>
        <p><strong>Quantity:</strong> <?= htmlspecialchars($selected_quantity); ?></p>
        <p><strong>Total:</strong> ₱<?= $total_price; ?></p>

        <!-- Hidden fields -->
        <input type="hidden" name="delivery_address" value="<?= htmlspecialchars($address); ?>">
        <input type="hidden" name="contact_number" value="<?= htmlspecialchars($contact); ?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product_name); ?>">
        <input type="hidden" name="product_price" value="<?= htmlspecialchars($product_price); ?>">
        <input type="hidden" name="size" value="<?= htmlspecialchars($selected_size); ?>">
        <input type="hidden" name="color" value="<?= htmlspecialchars($selected_color); ?>">
        <input type="hidden" name="quantity" value="<?= htmlspecialchars($selected_quantity); ?>">
        <input type="hidden" name="total" value="<?= $total_price; ?>">

        <br><br>
        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
