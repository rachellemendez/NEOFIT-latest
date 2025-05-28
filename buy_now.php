<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to proceed']);
    exit;
}

// Get POST data
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? '';
$size = $_POST['size'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);

// Validate input
if (empty($product_id) || empty($size)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Check if product exists and has enough stock
$stock_sql = "SELECT quantity_{$size} FROM products WHERE id = ?";
$stmt = $conn->prepare($stock_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$product = $result->fetch_assoc();
$available_stock = $product["quantity_{$size}"];

if ($quantity > $available_stock) {
    echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
    exit;
}

// Add to cart first
$insert_sql = "INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("iisi", $user_id, $product_id, $size, $quantity);

if ($stmt->execute()) {
    // Redirect to checkout with the cart item
    $cart_id = $conn->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Proceeding to checkout',
        'redirect' => "checkout.php?cart_id={$cart_id}"
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error processing request']);
}

$stmt->close();
$conn->close();