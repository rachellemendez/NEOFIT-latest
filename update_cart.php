<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'] ?? '';
$quantity = intval($data['quantity'] ?? 0);

// Validate input
if (empty($cart_id) || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get cart item and check stock
$check_sql = "SELECT c.*, 
                     CASE c.size 
                         WHEN 'small' THEN p.quantity_small
                         WHEN 'medium' THEN p.quantity_medium
                         WHEN 'large' THEN p.quantity_large
                     END as available_stock
              FROM cart c
              JOIN products p ON c.product_id = p.id
              WHERE c.id = ? AND c.user_id = ?";

$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit;
}

$cart_item = $result->fetch_assoc();

// Check if requested quantity is available
if ($quantity > $cart_item['available_stock']) {
    echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
    exit;
}

// Update quantity
$update_sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating cart']);
}

$stmt->close();
$conn->close();