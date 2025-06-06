<?php
include '../db.php';
session_start();

if (!isset($_SESSION['admin@1'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'stats':
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

    case 'get_requests':
        // Get pending requests
        $query = "SELECT * FROM neocreds_transactions WHERE status = 'pending' ORDER BY request_date DESC";
        $result = $conn->query($query);
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        echo json_encode(['status' => 'success', 'requests' => $requests]);
        break;

    case 'get_history':
        // Get processed transactions (approved or denied)
        $query = "SELECT * FROM neocreds_transactions WHERE status IN ('approved', 'denied') ORDER BY process_date DESC";
        $result = $conn->query($query);
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        echo json_encode(['status' => 'success', 'transactions' => $transactions]);
        break;

    default:
        // Handle transaction processing (approve/deny)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['request_id']) || !isset($_POST['action'])) {
                echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
                exit();
            }

            $request_id = intval($_POST['request_id']);
            $process_action = $_POST['action'];

            if (!in_array($process_action, ['approve', 'deny'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
                exit();
            }

            // Start transaction
            mysqli_begin_transaction($conn);

            try {
                // Get request details first
                $stmt = $conn->prepare("SELECT user_id, amount FROM neocreds_transactions WHERE id = ? AND status = 'pending'");
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
                $stmt->bind_param("si", $process_action, $request_id);
                $stmt->execute();

                // If approved, update user's neocreds balance
                if ($process_action === 'approve') {
                    $stmt = $conn->prepare("
                        UPDATE users 
                        SET neocreds = neocreds + ? 
                        WHERE id = ?
                    ");
                    $stmt->bind_param("di", $request['amount'], $request['user_id']);
                    $stmt->execute();
                }

                mysqli_commit($conn);
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
}

// Handle export functionality
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="neocreds_transactions.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['User', 'Email', 'Amount', 'Status', 'Request Date', 'Process Date', 'Processed By', 'Notes']);
    
    $query = "SELECT * FROM neocreds_transactions ORDER BY request_date DESC";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['user_name'],
            $row['user_email'],
            $row['amount'],
            $row['status'],
            $row['request_date'],
            $row['process_date'],
            $row['processed_by'],
            $row['admin_notes']
        ]);
    }
    
    fclose($output);
    exit();
} 