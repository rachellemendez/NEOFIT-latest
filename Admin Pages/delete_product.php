<?php
include '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No product ID provided']);
    exit;
}

$id = intval($_GET['id']);

try {
    // First get the images to delete
    $sql = "SELECT photoFront, photo1, photo2, photo3, photo4 FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // Delete the product from database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    // If product was successfully deleted, try to delete the images
    $failed_deletes = [];
    foreach (['photoFront', 'photo1', 'photo2', 'photo3', 'photo4'] as $field) {
        if (!empty($product[$field])) {
            $image_path = "../uploads/" . basename($product[$field]);
            if (file_exists($image_path)) {
                if (!@unlink($image_path)) {
                    $failed_deletes[] = basename($image_path);
                }
            }
        }
    }

    $message = 'Product deleted successfully';
    if (!empty($failed_deletes)) {
        $message .= ". Note: Some image files could not be deleted due to permissions.";
    }

    echo json_encode([
        'success' => true,
        'message' => $message,
        'failed_deletes' => $failed_deletes
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting product: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
