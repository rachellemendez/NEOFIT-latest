<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $status = $_POST['status'];
    
    // Update order in database - only update status
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
                   
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $order_id);

    if ($update_stmt->execute()) {
        header("Location: view_order.php?id=" . $order_id);
        exit();
    }
}

// Fetch order details with product information
$sql = "SELECT o.*, 
               p.product_name as product_display_name,
               p.product_price,
               p.photoFront as product_image,
               (o.total / o.quantity) as unit_price
        FROM orders o 
        LEFT JOIN products p ON o.product_id = p.id 
        WHERE o.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_order_details_page.php");
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Edit Order</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .order-form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .order-id {
            font-size: 1.5em;
            font-weight: bold;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .section-title {
            font-size: 1.2em;
            font-weight: 500;
            margin-bottom: 15px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }

        .form-control:focus {
            border-color: #7ab55c;
            outline: none;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-details {
            display: flex;
            gap: 20px;
            align-items: start;
        }

        .product-info {
            flex-grow: 1;
        }

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

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .save-btn {
            background: #7ab55c;
            color: white;
        }

        .cancel-btn {
            background: #6c757d;
            color: white;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .product-details {
                flex-direction: column;
            }

            .action-buttons {
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
            <a href="view_order.php?id=<?php echo $order_id; ?>" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Order Details
            </a>

            <form method="POST" class="order-form-container">
                <div class="order-header">
                    <div class="order-id">Edit Order #<?php echo $order['id']; ?></div>
                </div>

                <div class="form-grid">
                    <div class="form-section">
                        <h3 class="section-title">Product Information</h3>
                        <div class="product-details">
                            <?php if ($order['product_image']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($order['product_image']); ?>" alt="Product Image" class="product-image">
                            <?php endif; ?>
                            <div class="product-info">
                                <div class="form-group">
                                    <label class="form-label">Product Name</label>
                                    <div class="form-control-static"><?php echo htmlspecialchars($order['product_display_name']); ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Size</label>
                                    <div class="form-control-static"><?php echo strtoupper(htmlspecialchars($order['size'])); ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Unit Price</label>
                                    <div class="form-control-static">₱<?php echo number_format($order['unit_price'], 2); ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Quantity</label>
                                    <div class="form-control-static"><?php echo (int)$order['quantity']; ?> pc(s)</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Total Amount</label>
                                    <div class="form-control-static">₱<?php echo number_format($order['total'], 2); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Order Status</h3>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <?php
                                $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
                                foreach ($statuses as $status) {
                                    $selected = ($status === $order['status']) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Delivery Information</h3>
                        <div class="form-group">
                            <label class="form-label">Delivery Address</label>
                            <div class="form-control-static"><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <div class="form-control-static"><?php echo htmlspecialchars($order['contact_number']); ?></div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Customer Information</h3>
                        <div class="form-group">
                            <label class="form-label">Customer Name</label>
                            <div class="form-control-static"><?php echo htmlspecialchars($order['user_name']); ?></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="form-control-static"><?php echo htmlspecialchars($order['user_email']); ?></div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="action-btn save-btn">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                    <a href="view_order.php?id=<?php echo $order_id; ?>" class="action-btn cancel-btn">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Add client-side validation if needed
        document.querySelector('form').addEventListener('submit', function(e) {
            const quantity = document.querySelector('input[name="quantity"]').value;
            if (quantity < 1) {
                e.preventDefault();
                alert('Quantity must be at least 1');
            }
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?> 