<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to cart']);
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

// Check if item already exists in cart
$check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("iis", $user_id, $product_id, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing cart item
    $cart_item = $result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + $quantity;
    
    if ($new_quantity > $available_stock) {
        echo json_encode(['success' => false, 'message' => 'Cannot add more items than available stock']);
        exit;
    }

    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
} else {
    // Add new cart item
    $insert_sql = "INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iisi", $user_id, $product_id, $size, $quantity);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding item to cart']);
}

$stmt->close();
$conn->close();