<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Function to send error response
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

if (!$amount || $amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit();
}

try {
    // Get user details with concatenated name
    $stmt = $conn->prepare("
        SELECT 
            id, 
            email,
            CONCAT(first_name, ' ', last_name) as full_name 
        FROM users 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        throw new Exception('User not found');
    }

    // Insert new request
    $stmt = $conn->prepare("
        INSERT INTO neocreds_transactions 
        (user_id, user_name, user_email, amount, status, request_date) 
        VALUES (?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)
    ");
    $stmt->bind_param("issd", 
        $user['id'], 
        $user['full_name'], 
        $user['email'], 
        $amount
    );

    if (!$stmt->execute()) {
        throw new Exception('Failed to create request');
    }

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    error_log('NeoCreds request error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Handle GET requests for balance and history
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $user_email = $_SESSION['email'];
        
        // Get user's current balance and pending total
        $stmt = $conn->prepare("
            SELECT 
                u.neocreds as balance,
                COALESCE(SUM(CASE WHEN nt.status = 'pending' THEN nt.amount ELSE 0 END), 0) as pending_total
            FROM users u
            LEFT JOIN neocreds_transactions nt ON u.id = nt.user_id AND nt.status = 'pending'
            WHERE u.email = ?
            GROUP BY u.id, u.neocreds
        ");
        
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param("s", $user_email);
        if (!$stmt->execute()) {
            throw new Exception('Failed to fetch balance: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if (!$data) {
            // If no data found, return default values
            echo json_encode([
                'status' => 'success',
                'balance' => 0,
                'pending_total' => 0
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'balance' => $data['balance'] ?? 0,
                'pending_total' => $data['pending_total'] ?? 0
            ]);
        }

    } catch (Exception $e) {
        error_log('NeoCreds error: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} 