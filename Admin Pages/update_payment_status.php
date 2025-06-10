<?php
header('Content-Type: application/json');
include 'payment_functions.php';

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin@1'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$transaction_id = $_POST['transaction_id'] ?? '';
$new_status = $_POST['status'] ?? '';

// Validate status
if (!in_array($new_status, ['pending', 'success', 'failed'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

if (updatePaymentStatus($transaction_id, $new_status)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
}
?>
