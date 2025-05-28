<?php
include '../db.php';

// Get filters from URL
$user_filter = isset($_GET['user']) ? $_GET['user'] : null;
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;
$search_term = isset($_GET['search']) ? $_GET['search'] : null;
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;

// Base query
$sql = "SELECT o.*, 
               p.photoFront as product_image 
        FROM orders o 
        LEFT JOIN products p ON o.product_name = p.product_name WHERE 1=1";
$params = [];
$types = "";

// Add filters
if ($user_filter) {
    $sql .= " AND o.user_email = ?";
    $params[] = $user_filter;
    $types .= "s";
}

if ($status_filter) {
    $sql .= " AND o.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if ($search_term) {
    $search_term = "%$search_term%";
    $sql .= " AND (o.user_name LIKE ? OR o.product_name LIKE ? OR o.id LIKE ?)";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
    $types .= "sss";
}

if ($date_from) {
    $sql .= " AND DATE(o.order_date) >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if ($date_to) {
    $sql .= " AND DATE(o.order_date) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

// Add sorting
$sql .= " ORDER BY o.order_date DESC";

// Prepare and execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get order statistics
$stats_sql = "SELECT 
    COUNT(*) as total_orders,
    COUNT(DISTINCT user_name) as unique_customers,
    SUM(total) as total_revenue,
    COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending_orders,
    COUNT(CASE WHEN status = 'Processing' THEN 1 END) as processing_orders,
    COUNT(CASE WHEN status = 'Shipped' THEN 1 END) as shipped_orders,
    COUNT(CASE WHEN status = 'Delivered' THEN 1 END) as delivered_orders,
    COUNT(CASE WHEN status = 'Cancelled' THEN 1 END) as cancelled_orders
FROM orders";
$stats_result = $conn->query($stats_sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Order Management</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .filters-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            font-size: 0.9em;
            color: #666;
        }

        .filter-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }

        .apply-btn {
            background: #7ab55c;
            color: white;
        }

        .reset-btn {
            background: #6c757d;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            color: #666;
        }

        .order-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
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
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            object-fit: cover;
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
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .status-badge:hover {
            opacity: 0.8;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #c3e6cb; color: #1e7e34; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }

        .view-btn { background: #6c757d; color: white; }
        .edit-btn { background: #7ab55c; color: white; }
        .delete-btn { background: #dc3545; color: white; }

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

        .status-menu {
            position: absolute;
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: none;
            z-index: 100;
        }

        .status-menu.active {
            display: block;
        }

        .status-option {
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .status-option:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }

            .order-details {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
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
                    echo "Order Management";
                }
                ?>
            </h1>

            <!-- Order Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['total_orders']; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['unique_customers']; ?></div>
                    <div class="stat-label">Unique Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">₱<?php echo number_format($stats_result['total_revenue'], 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['pending_orders']; ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['processing_orders']; ?></div>
                    <div class="stat-label">Processing</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['shipped_orders']; ?></div>
                    <div class="stat-label">Shipped</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats_result['delivered_orders']; ?></div>
                    <div class="stat-label">Delivered</div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <form id="filterForm" method="GET">
                    <div class="filters-grid">
                        <div class="filter-item">
                            <label class="filter-label">Search</label>
                            <input type="text" name="search" class="filter-input" 
                                   placeholder="Order ID, Customer, or Product" 
                                   value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-input">
                                <option value="">All Statuses</option>
                                <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Processing" <?php echo $status_filter === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="Shipped" <?php echo $status_filter === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="Delivered" <?php echo $status_filter === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Cancelled" <?php echo $status_filter === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Date From</label>
                            <input type="date" name="date_from" class="filter-input" 
                                   value="<?php echo htmlspecialchars($date_from ?? ''); ?>">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Date To</label>
                            <input type="date" name="date_to" class="filter-input" 
                                   value="<?php echo htmlspecialchars($date_to ?? ''); ?>">
                        </div>
                    </div>
                    <div class="filter-buttons">
                        <button type="submit" class="filter-btn apply-btn">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <button type="button" class="filter-btn reset-btn" onclick="resetFilters()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Orders List -->
            <div class="orders-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status_class = 'status-' . strtolower($row['status']);
                        ?>
                        <div class="order-card">
                            <div class="order-header">
                                <h2>Order #<?php echo $row['id']; ?></h2>
                                <div class="status-container" data-order-id="<?php echo $row['id']; ?>">
                                    <span class="status-badge <?php echo $status_class; ?>" onclick="toggleStatusMenu(<?php echo $row['id']; ?>)">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                    <div class="status-menu" id="statusMenu_<?php echo $row['id']; ?>">
                                        <div class="status-option" onclick="updateStatus(<?php echo $row['id']; ?>, 'Pending')">Pending</div>
                                        <div class="status-option" onclick="updateStatus(<?php echo $row['id']; ?>, 'Processing')">Processing</div>
                                        <div class="status-option" onclick="updateStatus(<?php echo $row['id']; ?>, 'Shipped')">Shipped</div>
                                        <div class="status-option" onclick="updateStatus(<?php echo $row['id']; ?>, 'Delivered')">Delivered</div>
                                        <div class="status-option" onclick="updateStatus(<?php echo $row['id']; ?>, 'Cancelled')">Cancelled</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-details">
                                <div class="detail-item">
                                    <div class="detail-label">Customer</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                    <div class="detail-sub"><?php echo htmlspecialchars($row['user_email']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Product</div>
                                    <div class="product-info">
                                        <?php if ($row['product_image']): ?>
                                            <img src="<?php echo htmlspecialchars($row['product_image']); ?>" 
                                                 alt="Product" class="product-image">
                                        <?php endif; ?>
                                        <div>
                                            <div class="detail-value"><?php echo htmlspecialchars($row['product_name']); ?></div>
                                            <div class="detail-sub">Size: <?php echo strtoupper($row['size']); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Order Details</div>
                                    <div class="detail-value">₱<?php echo number_format($row['total'], 2); ?></div>
                                    <div class="detail-sub">Quantity: <?php echo $row['quantity']; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Shipping Info</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['delivery_address']); ?></div>
                                    <div class="detail-sub"><?php echo htmlspecialchars($row['contact_number']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Payment</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($row['payment_method']); ?></div>
                                    <div class="detail-sub">Order Date: <?php echo date('M d, Y', strtotime($row['order_date'])); ?></div>
                                </div>
                            </div>

                            <div class="order-actions">
                                <button class="action-btn view-btn" onclick="viewOrderDetails(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                                <button class="action-btn edit-btn" onclick="editOrder(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-edit"></i> Edit Order
                                </button>
                                <button class="action-btn delete-btn" onclick="deleteOrder(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No orders found</p>";
                }
                ?>
            </div>
        </main>
    </div>

    <script>
        function resetFilters() {
            document.querySelectorAll('.filter-input').forEach(input => {
                input.value = '';
            });
            document.getElementById('filterForm').submit();
        }

        function toggleStatusMenu(orderId) {
            const menu = document.getElementById(`statusMenu_${orderId}`);
            document.querySelectorAll('.status-menu').forEach(m => {
                if (m.id !== `statusMenu_${orderId}`) {
                    m.classList.remove('active');
                }
            });
            menu.classList.toggle('active');
        }

        function updateStatus(orderId, newStatus) {
            // Send AJAX request to update status
            fetch('update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const statusBadge = document.querySelector(`[data-order-id="${orderId}"] .status-badge`);
                    statusBadge.className = `status-badge status-${newStatus.toLowerCase()}`;
                    statusBadge.innerHTML = newStatus + ' <i class="fas fa-chevron-down"></i>';
                    
                    // Hide menu
                    document.getElementById(`statusMenu_${orderId}`).classList.remove('active');
                    
                    // Show success message
                    alert('Order status updated successfully');
                } else {
                    alert('Failed to update order status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the order status');
            });
        }

        function viewOrderDetails(orderId) {
            // Implement view details functionality
            window.location.href = `view_order.php?id=${orderId}`;
        }

        function editOrder(orderId) {
            // Implement edit order functionality
            window.location.href = `edit_order.php?id=${orderId}`;
        }

        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Send AJAX request to delete order
                fetch('delete_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove order card from UI
                        const orderCard = document.querySelector(`[data-order-id="${orderId}"]`).closest('.order-card');
                        orderCard.remove();
                        alert('Order deleted successfully');
                    } else {
                        alert('Failed to delete order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the order');
                });
            }
        }

        // Close status menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.status-container')) {
                document.querySelectorAll('.status-menu').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>