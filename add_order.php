<?php
session_start();

// Check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['user_name'])) {
    die("You must be logged in to place an order.");
}

include 'db.php';

// Get form data
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? '';
$product_name = $_POST['product_name'] ?? '';
$product_price = $_POST['product_price'] ?? 0.00;
$size = $_POST['size'] ?? '';
$color = $_POST['color'] ?? '';
$quantity = $_POST['quantity'] ?? 1;
$payment_method = $_POST['payment_method'] ?? 'Unknown';
$delivery_address = $_POST['delivery_address'] ?? 'Unknown';
$contact_number = $_POST['contact_number'] ?? 'Unknown';
$status = 'Pending';

$total_price = $product_price * $quantity;

// Get user data from session
$user_email = $_SESSION['email'];
$user_name = $_SESSION['user_name'];

// Insert order into DB
$sql = "INSERT INTO orders (
    user_id, user_name, user_email,
    total_amount, payment_method, delivery_address,
    contact_number, status
) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issdsss", 
    $user_id, $user_name, $user_email,
    $total_price, $payment_method, $delivery_address,
    $contact_number
);

if (!$stmt->execute()) {
    die("Error creating order: " . $conn->error);
}

$order_id = $conn->insert_id;

// Insert order item
$item_sql = "INSERT INTO order_items (order_id, product_id, quantity, size, price) VALUES (?, ?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_sql);
$item_stmt->bind_param("iiids", $order_id, $product_id, $quantity, $size, $product_price);

if (!$item_stmt->execute()) {
    die("Error creating order item: " . $conn->error);
}

// Update product stock
$size_column = '';
switch (strtolower($size)) {
    case 'small':
        $size_column = 'quantity_small';
        break;
    case 'medium':
        $size_column = 'quantity_medium';
        break;
    case 'large':
        $size_column = 'quantity_large';
        break;
    default:
        die("Invalid size");
}

$update_stock_sql = "UPDATE products SET $size_column = $size_column - ? WHERE id = ?";
$stock_stmt = $conn->prepare($update_stock_sql);
$stock_stmt->bind_param("ii", $quantity, $product_id);

if (!$stock_stmt->execute()) {
    die("Error updating stock: " . $conn->error);
}

// Create payment record
$transaction_id = 'TXN' . time() . mt_rand(1000, 9999);
$initial_status = ($payment_method === 'NeoCreds') ? 'success' : 'pending';

$payment_sql = "INSERT INTO payments (transaction_id, order_id, user_name, amount, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
$payment_stmt = $conn->prepare($payment_sql);
$payment_stmt->bind_param("sisdss", $transaction_id, $order_id, $user_name, $total_price, $payment_method, $initial_status);

if (!$payment_stmt->execute()) {
    die("Error creating payment record: " . $conn->error);
}

// Success response
echo json_encode(['success' => true, 'message' => 'Order placed successfully']);

$stmt->close();
$conn->close();
?>
