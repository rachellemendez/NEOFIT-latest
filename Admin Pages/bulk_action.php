<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display in output

include '../db.php';

header('Content-Type: application/json');

// Log incoming request
error_log("Bulk action request received: " . file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Log decoded data
error_log("Decoded data: " . print_r($data, true));

if (!isset($data['action']) || !isset($data['products']) || empty($data['products'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit;
}

$action = strtolower($data['action']); // Convert action to lowercase
$products = array_map('intval', $data['products']); // Ensure all IDs are integers

// Validate product IDs
$invalid_ids = array_filter($products, function($id) { return $id <= 0; });
if (!empty($invalid_ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid product IDs detected']);
    exit;
}

try {
    // Prepare product IDs for SQL using prepared statement
    $placeholders = str_repeat('?,', count($products) - 1) . '?';
    $types = str_repeat('i', count($products));

    switch ($action) {
        case 'delete':
            // First get the images to delete
            $sql = "SELECT id, photoFront, photo1, photo2, photo3, photo4 FROM products WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param($types, ...$products);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $images_to_delete = [];
            
            while ($row = $result->fetch_assoc()) {
                foreach (['photoFront', 'photo1', 'photo2', 'photo3', 'photo4'] as $field) {
                    if (!empty($row[$field])) {
                        $images_to_delete[] = $row[$field];
                    }
                }
            }
            $stmt->close();

            // Delete products from database first
            $sql = "DELETE FROM products WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param($types, ...$products);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Try to delete associated images, but continue even if some fail
            $failed_deletes = [];
            foreach ($images_to_delete as $image) {
                $image_path = "../uploads/" . basename($image);
                if (file_exists($image_path)) {
                    if (!@unlink($image_path)) {
                        $failed_deletes[] = basename($image_path);
                        error_log("Failed to delete file: " . $image_path . " - " . error_get_last()['message']);
                    }
                }
            }
            
            $message = count($products) . ' products deleted successfully from database';
            if (!empty($failed_deletes)) {
                $message .= ". Note: Some image files could not be deleted due to permissions.";
            }
            
            echo json_encode([
                'success' => true, 
                'message' => $message,
                'failed_deletes' => $failed_deletes
            ]);
            $stmt->close();
            break;

        case 'publish':
            $sql = "UPDATE products SET product_status = 'live' WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param($types, ...$products);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            echo json_encode(['success' => true, 'message' => count($products) . ' products published successfully']);
            $stmt->close();
            break;

        case 'unpublish':
            $sql = "UPDATE products SET product_status = 'unpublished' WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param($types, ...$products);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            echo json_encode(['success' => true, 'message' => count($products) . ' products unpublished successfully']);
            $stmt->close();
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
    }
} catch (Exception $e) {
    error_log("Bulk action error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error performing bulk action: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 