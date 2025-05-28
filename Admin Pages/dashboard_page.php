<?php
include '../db.php';

// Get today's sales
$today = date('Y-m-d');
$sql_today_sales = "SELECT COALESCE(SUM(total), 0) as today_sales, COUNT(*) as today_orders 
                    FROM orders 
                    WHERE DATE(order_date) = ?";
$stmt = $conn->prepare($sql_today_sales);
$stmt->bind_param("s", $today);
$stmt->execute();
$today_result = $stmt->get_result()->fetch_assoc();

// Get yesterday's sales for comparison
$yesterday = date('Y-m-d', strtotime('-1 day'));
$sql_yesterday_sales = "SELECT COALESCE(SUM(total), 0) as yesterday_sales 
                       FROM orders 
                       WHERE DATE(order_date) = ?";
$stmt = $conn->prepare($sql_yesterday_sales);
$stmt->bind_param("s", $yesterday);
$stmt->execute();
$yesterday_result = $stmt->get_result()->fetch_assoc();

// Calculate sales trend
$sales_trend = 0;
if ($yesterday_result['yesterday_sales'] > 0) {
    $sales_trend = (($today_result['today_sales'] - $yesterday_result['yesterday_sales']) / $yesterday_result['yesterday_sales']) * 100;
}

// Get total orders for last 30 days
$last_month = date('Y-m-d', strtotime('-30 days'));
$sql_month_orders = "SELECT COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_sales 
                    FROM orders 
                    WHERE order_date >= ?";
$stmt = $conn->prepare($sql_month_orders);
$stmt->bind_param("s", $last_month);
$stmt->execute();
$month_result = $stmt->get_result()->fetch_assoc();

// Calculate average order value
$avg_order_value = 0;
if ($month_result['total_orders'] > 0) {
    $avg_order_value = $month_result['total_sales'] / $month_result['total_orders'];
}

// Get recent activity (last 10 orders/events)
$sql_recent = "SELECT id, user_name, product_name, status, order_date, total 
               FROM orders 
               ORDER BY order_date DESC 
               LIMIT 10";
$recent_result = $conn->query($sql_recent);

// Get low stock items
$sql_low_stock = "SELECT product_name, 
                         quantity_small + quantity_medium + quantity_large as total_stock,
                         product_status,
                         id
                  FROM products 
                  WHERE (quantity_small + quantity_medium + quantity_large) <= 5
                  ORDER BY (quantity_small + quantity_medium + quantity_large) ASC
                  LIMIT 5";
$low_stock_result = $conn->query($sql_low_stock);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 1em;
        }

        .value {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .trend {
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trend.up { color: #28a745; }
        .trend.down { color: #dc3545; }

        .stat-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2em;
            opacity: 0.1;
        }

        .activity-feed {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-header h3 {
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-content p {
            margin: 0;
        }

        .activity-time {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .inventory-items {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .inventory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .inventory-header h3 {
            margin: 0;
        }

        .stock-level {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .low-stock {
            background: #fff3cd;
            color: #856404;
        }

        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }

        .in-stock {
            background: #d4edda;
            color: #155724;
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
                <li class="active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </li>
                <li onclick="window.location.href='manage_order_details_page.php'">
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
            <h1 class="page-title">Dashboard</h1>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Today's Sales</h3>
                    <div class="value">$<?php echo number_format($today_result['today_sales'], 2); ?></div>
                    <div class="trend <?php echo $sales_trend >= 0 ? 'up' : 'down'; ?>">
                        <i class="fas fa-arrow-<?php echo $sales_trend >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo abs(round($sales_trend, 1)); ?>% from yesterday
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Total Orders (30 days)</h3>
                    <div class="value"><?php echo $month_result['total_orders']; ?></div>
                    <div class="trend up">
                        <i class="fas fa-shopping-bag"></i>
                        Last 30 days
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Average Order Value</h3>
                    <div class="value">$<?php echo number_format($avg_order_value, 2); ?></div>
                    <div class="trend">
                        <i class="fas fa-calculator"></i>
                        30-day average
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Today's Orders</h3>
                    <div class="value"><?php echo $today_result['today_orders']; ?></div>
                    <div class="trend">
                        <i class="fas fa-clock"></i>
                        Today's count
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-feed">
                <div class="activity-header">
                    <h3>Recent Activity</h3>
                    <a href="manage_order_details_page.php" style="text-decoration: none; color: #7ab55c;">View All</a>
                </div>
                
                <?php
                if ($recent_result->num_rows > 0) {
                    while ($row = $recent_result->fetch_assoc()) {
                        $time_ago = time_elapsed_string($row['order_date']);
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <p>Order #<?php echo $row['id']; ?> - <?php echo htmlspecialchars($row['user_name']); ?> 
                                   ordered <?php echo htmlspecialchars($row['product_name']); ?> 
                                   ($<?php echo number_format($row['total'], 2); ?>)</p>
                                <div class="activity-time"><?php echo $time_ago; ?></div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No recent activity</p>";
                }
                ?>
            </div>

            <!-- Low Stock Items -->
            <div class="inventory-items">
                <div class="inventory-header">
                    <h3>Low Stock Items</h3>
                    <a href="all_product_page.php" class="btn-apply">Manage Inventory</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($low_stock_result->num_rows > 0) {
                            while ($row = $low_stock_result->fetch_assoc()) {
                                $stock_class = $row['total_stock'] == 0 ? 'out-of-stock' : 'low-stock';
                                $stock_text = $row['total_stock'] == 0 ? 'Out of Stock' : 'Low Stock';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo $row['total_stock']; ?></td>
                                    <td><span class="stock-level <?php echo $stock_class; ?>"><?php echo $stock_text; ?></span></td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-track">Update Stock</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No low stock items</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

<?php
// Helper function to format time elapsed
function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d > 0) {
        return $diff->d . " day" . ($diff->d > 1 ? "s" : "") . " ago";
    }
    if ($diff->h > 0) {
        return $diff->h . " hour" . ($diff->h > 1 ? "s" : "") . " ago";
    }
    if ($diff->i > 0) {
        return $diff->i . " minute" . ($diff->i > 1 ? "s" : "") . " ago";
    }
    return "just now";
}

$conn->close();
?>