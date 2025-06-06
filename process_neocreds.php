<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_email = $_SESSION['email'];

// Get user ID
$stmt = $conn->prepare("SELECT id, neocreds FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'balance':
        // Get current balance and pending total
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(amount), 0) as pending_total 
            FROM neocreds_transactions 
            WHERE user_id = ? AND status = 'pending'
        ");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $pending = $stmt->get_result()->fetch_assoc();

        echo json_encode([
            'status' => 'success',
            'balance' => $user['neocreds'],
            'pending_total' => $pending['pending_total']
        ]);
        break;

    case 'history':
        // Get transaction history
        $stmt = $conn->prepare("
            SELECT * FROM neocreds_transactions 
            WHERE user_id = ? 
            ORDER BY request_date DESC
        ");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        echo json_encode([
            'status' => 'success',
            'transactions' => $transactions
        ]);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
} 