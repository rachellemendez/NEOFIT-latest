<?php
session_start();
if (!isset($_SESSION['admin@1'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
require_once dirname(__FILE__) . '/../db_connection.php';
require_once 'payment_functions.php';

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$date = $_GET['date'] ?? '';
$page = intval($_GET['page'] ?? 1);

// Get filtered payments
$payments = getFilteredPayments($search, $status, $date, $page);
$total = getFilteredPaymentsCount($search, $status, $date);

// Format the payments data
$formatted_payments = array_map(function($payment) {
    return [
        'transaction_id' => $payment['transaction_id'],
        'order_id' => $payment['order_id'],
        'customer_name' => $payment['customer_name'],
        'date' => date('M d, Y H:i', strtotime($payment['payment_date'])),
        'amount' => number_format($payment['amount'], 2),
        'payment_method' => ucfirst($payment['payment_method']),
        'status' => $payment['status'],
    ];
}, $payments);

// Return JSON response
echo json_encode([
    'payments' => $formatted_payments,
    'total' => $total,
    'total_pages' => ceil($total / 10)
]);
?> 