<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get active tab from URL parameter, default to 'all'
$active_tab = isset($_GET['status']) ? strtolower($_GET['status']) : 'all';

// Map old status names to new ones for backward compatibility
$status_map = [
    'pending' => 'to_pack',
    'processing' => 'packed',
    'shipped' => 'in_transit'
];

if (isset($status_map[$active_tab])) {
    $active_tab = $status_map[$active_tab];
}

// Get order counts for each status
$count_sql = "SELECT 
    COUNT(CASE WHEN status = 'To Pack' THEN 1 END) as to_pack_count,
    COUNT(CASE WHEN status = 'Packed' THEN 1 END) as packed_count,
    COUNT(CASE WHEN status = 'In Transit' THEN 1 END) as in_transit_count,
    COUNT(CASE WHEN status = 'Delivered' THEN 1 END) as delivered_count,
    COUNT(CASE WHEN status = 'Cancelled' THEN 1 END) as cancelled_count,
    COUNT(CASE WHEN status = 'Returned' THEN 1 END) as returned_count,
    COUNT(*) as total_count
    FROM orders 
    WHERE user_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$counts = $count_stmt->get_result()->fetch_assoc();

// Get user's orders based on status filter
$sql = "SELECT o.*, oi.quantity, oi.size, p.product_name, p.photoFront, p.product_price as price,
        (oi.quantity * p.product_price) as item_total
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?";

// Add status filter if not showing all
if ($active_tab !== 'all') {
    // Convert status format to match database
    $status_display = str_replace('_', ' ', ucwords($active_tab));
    $sql .= " AND o.status = ?";
}
$sql .= " ORDER BY o.order_date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);

if ($active_tab !== 'all') {
    $stmt->bind_param("is", $user_id, $status_display);
} else {
    $stmt->bind_param("i", $user_id);
}

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

        .status-to-pack {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-packed {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-in-transit {
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
        
        .status-returned {
            background-color: #e2e3e5;
            color: #383d41;
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

        .order-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .tab {
            padding: 10px 20px;
            background-color: #f5f5f5;
            border-radius: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .tab:hover {
            background-color: #e0e0e0;
            color: #333;
        }

        .tab.active {
            background-color: #55a39b;
            color: white;
        }

        @media (max-width: 768px) {
            .order-tabs {
                padding-bottom: 10px;
            }
            
            .tab {
                padding: 8px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">My Orders</h1>
            <a href="landing_page.php" class="continue-shopping">Continue Shopping</a>
        </div>

        <div class="order-tabs">
            <a href="?status=all" class="tab <?php echo $active_tab === 'all' ? 'active' : ''; ?>">
                All Orders (<?php echo $counts['total_count']; ?>)
            </a>
            <a href="?status=to_pack" class="tab <?php echo $active_tab === 'to_pack' ? 'active' : ''; ?>">
                To Pack (<?php echo $counts['to_pack_count']; ?>)
            </a>
            <a href="?status=packed" class="tab <?php echo $active_tab === 'packed' ? 'active' : ''; ?>">
                Packed (<?php echo $counts['packed_count']; ?>)
            </a>
            <a href="?status=in_transit" class="tab <?php echo $active_tab === 'in_transit' ? 'active' : ''; ?>">
                In Transit (<?php echo $counts['in_transit_count']; ?>)
            </a>
            <a href="?status=delivered" class="tab <?php echo $active_tab === 'delivered' ? 'active' : ''; ?>">
                Delivered (<?php echo $counts['delivered_count']; ?>)
            </a>
            <a href="?status=cancelled" class="tab <?php echo $active_tab === 'cancelled' ? 'active' : ''; ?>">
                Cancelled (<?php echo $counts['cancelled_count']; ?>)
            </a>
            <a href="?status=returned" class="tab <?php echo $active_tab === 'returned' ? 'active' : ''; ?>">
                Returned (<?php echo $counts['returned_count']; ?>)
            </a>
        </div>

        <div class="orders-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></span>
                            <span class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></span>
                        </div>

                        <div class="order-content">
                            <div class="order-item">
                                <img src="Admin Pages/<?php echo htmlspecialchars($order['photoFront']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="item-image">
                                <div class="item-details">
                                    <div class="item-name"><?php echo htmlspecialchars($order['product_name']); ?></div>
                                    <div class="item-size">Size: <?php echo strtoupper(htmlspecialchars($order['size'])); ?></div>
                                    <div class="item-quantity">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></div>
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
                            <div class="order-total">Total: ₱<?php echo number_format($order['item_total'], 2); ?></div>
                            <div class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-orders">
                    <h2>No orders found for this status</h2>
                    <p>You don't have any orders with the selected status.</p>
                    <a href="?status=all" class="shop-now-btn">View All Orders</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>