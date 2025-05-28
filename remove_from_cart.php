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

// Validate input
if (empty($cart_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Delete cart item
$delete_sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing item from cart']);
}

$stmt->close();
$conn->close();