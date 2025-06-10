<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Missing order ID']);
    exit();
}

// Delete order
$sql = "DELETE FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);

$success = $stmt->execute();
$stmt->close();
$conn->close();

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Order deleted successfully' : 'Failed to delete order'
]);
?> 