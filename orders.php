<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's orders
$sql = "SELECT o.*, p.photoFront
        FROM orders o
        LEFT JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - My Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Alexandria', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
        }

        .continue-shopping {
            color: #55a39b;
            text-decoration: none;
        }

        .orders-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .order-header {
            padding: 15px 20px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-id {
            font-weight: bold;
            color: #000;
        }

        .order-date {
            color: #666;
            font-size: 14px;
        }

        .order-content {
            padding: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .item-size {
            color: #666;
            font-size: 14px;
        }

        .item-price {
            color: #55a39b;
            font-weight: bold;
        }

        .order-footer {
            padding: 15px 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total {
            font-weight: bold;
            font-size: 18px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background-color: #55a39b;
            color: #fff;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .shipping-info {
            margin-top: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }

        .shipping-info h4 {
            margin-bottom: 10px;
            color: #666;
        }

        .empty-orders {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .empty-orders h2 {
            margin-bottom: 10px;
        }

        .empty-orders p {
            color: #666;
            margin-bottom: 20px;
        }

        .shop-now-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #55a39b;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .shop-now-btn:hover {
            background-color: #478c85;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">My Orders</h1>
            <a href="landing_page.php" class="continue-shopping">Continue Shopping</a>
        </div>

        <div class="orders-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #<?php echo $order['id']; ?></span>
                            <span class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></span>
                        </div>

                        <div class="order-content">
                            <div class="order-item">
                                <img src="Admin Pages/<?php echo $order['photoFront']; ?>" alt="<?php echo $order['product_name']; ?>" class="item-image">
                                <div class="item-details">
                                    <div class="item-name"><?php echo $order['product_name']; ?></div>
                                    <div class="item-size">Size: <?php echo strtoupper($order['size']); ?></div>
                                    <div class="item-quantity">Quantity: <?php echo $order['quantity']; ?></div>
                                    <div class="item-price">₱<?php echo number_format($order['price'], 2); ?></div>
                                </div>
                            </div>

                            <div class="shipping-info">
                                <h4>Shipping Information</h4>
                                <p><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                <p>Contact: <?php echo htmlspecialchars($order['contact_number']); ?></p>
                                <p>Payment Method: <?php echo htmlspecialchars($order['payment_method']); ?></p>
                            </div>
                        </div>

                        <div class="order-footer">
                            <div class="order-total">Total: ₱<?php echo number_format($order['total'], 2); ?></div>
                            <div class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                <?php echo $order['status']; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-orders">
                    <h2>No orders yet</h2>
                    <p>Start shopping to see your orders here.</p>
                    <a href="landing_page.php" class="shop-now-btn">Shop Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>