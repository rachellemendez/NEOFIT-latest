<?php
// Prevent any unwanted output
ob_start();

// Include required files
require_once '../db.php';

// Set JSON content type header
header('Content-Type: application/json');

// Function to send JSON response and exit
function sendJsonResponse($success, $message, $data = null) {
    ob_clean(); // Clear any output
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

try {
    // Validate request method and parameters
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    if (!isset($_POST['order_id']) || !isset($_POST['new_status'])) {
        throw new Exception('Missing required parameters');
    }

    // Get and validate parameters
    $order_id = (int)$_POST['order_id'];
    $new_status = strtolower(trim($_POST['new_status']));

    if ($order_id <= 0) {
        throw new Exception('Invalid order ID');
    }

    // Check if order exists and get current status
    $check_stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    if (!$check_stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $check_stmt->bind_param("i", $order_id);
    if (!$check_stmt->execute()) {
        throw new Exception('Failed to check order: ' . $check_stmt->error);
    }

    $result = $check_stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('Order not found');
    }

    $row = $result->fetch_assoc();
    $current_status = strtolower($row['status']);

    // Update the status
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if (!$update_stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $update_stmt->bind_param("si", $new_status, $order_id);
    if (!$update_stmt->execute()) {
        throw new Exception('Failed to update status: ' . $update_stmt->error);
    }

    if ($update_stmt->affected_rows > 0) {
        sendJsonResponse(true, "Order status updated from '$current_status' to '$new_status'");
    } else {
        sendJsonResponse(false, "No changes made. Status might already be '$new_status'");
    }

    $check_stmt->close();
    $update_stmt->close();

} catch (Exception $e) {
    sendJsonResponse(false, $e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 