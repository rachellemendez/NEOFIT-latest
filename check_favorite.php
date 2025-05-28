<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

// Get query parameters
$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'] ?? '';

// Validate input
if (empty($product_id)) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

// Check if product is in favorites
$check_sql = "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['is_favorite' => $result->num_rows > 0]);

 