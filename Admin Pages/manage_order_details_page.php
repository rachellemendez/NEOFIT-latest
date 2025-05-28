<?php
include '../db.php';

// Get user filter from URL if provided
$user_filter = isset($_GET['user']) ? $_GET['user'] : null;

// Prepare the SQL query based on whether we're filtering by user
if ($user_filter) {
    $sql = "SELECT * FROM orders WHERE user_email = ? ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Order Details</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .order-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }

        .detail-label {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1.1em;
            font-weight: 500;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #c3e6cb; color: #1e7e34; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>NEOFIT</h1>
            <span class="admin-tag">Admin</span>
        </div>
        <div class="user-icon">
            <i class="fas fa-user-circle"></i>
        </div>
    </header>
    
    <div class="container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li onclick="window.location.href='dashboard_page.php'">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </li>
                <li class="active">
                    <i class="fas fa-list"></i>
                    <span>Manage Orders</span>
                </li>
                <li onclick="window.location.href='customer_orders_page.php'">
                    <i class="fas fa-users"></i>
                    <span>Customer Orders</span>
                </li>
                <li onclick="window.location.href='all_product_page.php'">
                    <i class="fas fa-tshirt"></i>
                    <span>All Products</span>
                </li>
                <li onclick="window.location.href='add_new_product_page.php'">
                    <i class="fas fa-plus-square"></i>
                    <span>Add New Product</span>
                </li>
                <li onclick="window.location.href='payments_page.php'">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </li>
                <li onclick="window.location.href='settings.php'">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <?php if ($user_filter): ?>
                <a href="customer_orders_page.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to All Customers
                </a>
            <?php endif; ?>

            <h1 class="page-title">
                <?php 
                if ($user_filter) {
                    $user_name = $result->num_rows > 0 ? $result->fetch_array()['user_name'] : 'Unknown User';
                    $result->data_seek(0); // Reset result pointer
                    echo "Orders for " . htmlspecialchars($user_name);
                } else {
                    echo "All Orders";
                }
                ?>
            </h1>
            
            <div class="orders-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status_class = 'status-' . strtolower($row['status']);
                        ?>
                        <div class="order-card">
                            <div class="order-header">
                                <h2>Order #<?php echo $row['id']; ?></h2>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            
                            <div class="order-details">
                                <div class="detail-item">
                                    <div class="detail-label">Customer</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Product</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['product_name']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Size</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['size']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Quantity</div>
                                    <div class="detail-value"><?php echo $row['quantity']; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Price</div>
                                    <div class="detail-value">$<?php echo number_format($row['total'], 2); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Order Date</div>
                                    <div class="detail-value"><?php echo date('M d, Y', strtotime($row['order_date'])); ?></div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">Delivery Address</div>
                                <div class="detail-value"><?php echo htmlspecialchars($row['delivery_address']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Contact Number</div>
                                <div class="detail-value"><?php echo htmlspecialchars($row['contact_number']); ?></div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    if ($user_filter) {
                        echo "<p>No orders found for this customer.</p>";
                    } else {
                        echo "<p>No orders found.</p>";
                    }
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>