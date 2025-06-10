<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to place an order']);
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'] ?? '';
$delivery_address = $_POST['delivery_address'] ?? '';
$contact_number = $_POST['contact_number'] ?? '';
$cart_id = $_POST['cart_id'] ?? null;

// Validate input
if (empty($payment_method) || empty($delivery_address) || empty($contact_number)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Get cart items
    if ($cart_id) {
        // Single item checkout
        $sql = "SELECT c.*, p.product_name, p.product_price, 
                CASE c.size 
                    WHEN 'small' THEN p.quantity_small
                    WHEN 'medium' THEN p.quantity_medium
                    WHEN 'large' THEN p.quantity_large
                END as available_stock
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.id = ? AND c.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_id, $user_id);
    } else {
        // Full cart checkout
        $sql = "SELECT c.*, p.product_name, p.product_price,
                CASE c.size 
                    WHEN 'small' THEN p.quantity_small
                    WHEN 'medium' THEN p.quantity_medium
                    WHEN 'large' THEN p.quantity_large
                END as available_stock
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    }

    $stmt->execute();
    $cart_items = $stmt->get_result();

    if ($cart_items->num_rows === 0) {
        throw new Exception('No items found in cart');
    }

    // Process each cart item
    while ($item = $cart_items->fetch_assoc()) {
        // Check stock availability
        if ($item['quantity'] > $item['available_stock']) {
            throw new Exception("Not enough stock available for {$item['product_name']}");
        }

        // Calculate total
        $total = $item['quantity'] * $item['product_price'];

        // Validate size
        $size = $item['size'] ?? 'N/A';
        if (empty($size) || $size === '0') {
            $size = 'N/A';
        }

        // Validate payment method
        if (empty($payment_method) || $payment_method === '0') {
            throw new Exception('Please select a valid payment method');
        }

        // Insert order
        $order_sql = "INSERT INTO orders (
            user_id, user_name, user_email,
            product_id, product_name, price,
            size, quantity, total,
            payment_method, delivery_address, contact_number,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param(
            "issiisdiddss",
            $user_id,
            $_SESSION['user_name'],
            $_SESSION['email'],
            $item['product_id'],
            $item['product_name'],
            $item['product_price'],
            $size,
            $item['quantity'],
            $total,
            $payment_method,
            $delivery_address,
            $contact_number
        );
        $order_stmt->execute();

        // Update product stock
        $update_stock_sql = "UPDATE products SET ";
        switch ($item['size']) {
            case 'small':
                $update_stock_sql .= "quantity_small = quantity_small - ?";
                break;
            case 'medium':
                $update_stock_sql .= "quantity_medium = quantity_medium - ?";
                break;
            case 'large':
                $update_stock_sql .= "quantity_large = quantity_large - ?";
                break;
        }
        $update_stock_sql .= " WHERE id = ?";

        $update_stock_stmt = $conn->prepare($update_stock_sql);
        $update_stock_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $update_stock_stmt->execute();

        // Remove item from cart
        $delete_cart_sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $delete_cart_stmt = $conn->prepare($delete_cart_sql);
        $delete_cart_stmt->bind_param("ii", $item['id'], $user_id);
        $delete_cart_stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'redirect' => 'orders.php'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>