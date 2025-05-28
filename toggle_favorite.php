<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add favorites']);
    exit;
}

// Get POST data
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? '';

// Validate input
if (empty($product_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Check if product exists
$check_product = "SELECT id FROM products WHERE id = ?";
$stmt = $conn->prepare($check_product);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check if product is already in favorites
$check_favorite = "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($check_favorite);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove from favorites
    $delete_sql = "DELETE FROM favorites WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Removed from favorites', 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error removing from favorites']);
    }
} else {
    // Add to favorites
    $insert_sql = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Added to favorites', 'action' => 'added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding to favorites']);
    }
}

$stmt->close();
$conn->close();