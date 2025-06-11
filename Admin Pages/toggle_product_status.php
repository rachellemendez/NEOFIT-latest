<?php
require_once '../db.php';

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

$id = $data['id'];
$status = $data['status'];

// Validate status
if (!in_array($status, ['live', 'unpublished'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid status value'
    ]);
    exit;
}

// Update product status
$stmt = $conn->prepare("UPDATE products SET product_status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Product status updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error updating product status: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>
