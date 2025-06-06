<?php
session_start();
include '../db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Check admin authentication
if (!isset($_SESSION['admin@1'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Handle POST request for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit();
    }

    $request_id = intval($_POST['request_id']);
    $new_status = $_POST['status'];

    // Validate status
    if (!in_array($new_status, ['approved', 'denied'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
        exit();
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Get request details and verify it's pending
        $stmt = $conn->prepare("SELECT * FROM neocreds_transactions WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $request = $result->fetch_assoc();

        if (!$request) {
            throw new Exception('Request not found or already processed');
        }

        // Update transaction status
        $stmt = $conn->prepare("
            UPDATE neocreds_transactions 
            SET status = ?, 
                process_date = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $new_status, $request_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update status');
        }

        // If approved, update user's balance
        if ($new_status === 'approved') {
            $stmt = $conn->prepare("
                UPDATE users 
                SET neocreds = neocreds + ? 
                WHERE id = ?
            ");
            $stmt->bind_param("di", $request['amount'], $request['user_id']);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to update user balance');
            }
        }

        mysqli_commit($conn);
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log('NeoCreds Error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// Handle GET requests
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_requests':
        // Get pending requests
        $result = $conn->query("
            SELECT * FROM neocreds_transactions 
            WHERE status = 'pending' 
            ORDER BY request_date DESC
        ");
        
        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
            exit();
        }
        
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        
        echo json_encode(['status' => 'success', 'requests' => $requests]);
        break;

    case 'get_history':
        // Get processed transactions
        $result = $conn->query("
            SELECT * FROM neocreds_transactions 
            WHERE status IN ('approved', 'denied') 
            ORDER BY process_date DESC
        ");
        
        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
            exit();
        }
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        
        echo json_encode(['status' => 'success', 'transactions' => $transactions]);
        break;

    case 'get_stats':
        // Get statistics
        $stats = [
            'pending_count' => 0,
            'approved_count' => 0,
            'denied_count' => 0,
            'total_neocreds' => 0
        ];

        // Get pending count
        $result = $conn->query("SELECT COUNT(*) as count FROM neocreds_transactions WHERE status = 'pending'");
        $stats['pending_count'] = $result->fetch_assoc()['count'];

        // Get approved count and total
        $result = $conn->query("
            SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
            FROM neocreds_transactions 
            WHERE status = 'approved'
        ");
        $approved = $result->fetch_assoc();
        $stats['approved_count'] = $approved['count'];
        $stats['total_neocreds'] = $approved['total'];

        // Get denied count
        $result = $conn->query("SELECT COUNT(*) as count FROM neocreds_transactions WHERE status = 'denied'");
        $stats['denied_count'] = $result->fetch_assoc()['count'];

        echo json_encode(['status' => 'success'] + $stats);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
} 