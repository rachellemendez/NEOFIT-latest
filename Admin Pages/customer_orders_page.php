<?php
include '../db.php';

// Get all unique customers with their order counts and total spent
$sql = "SELECT 
            user_name,
            user_email,
            COUNT(*) as total_orders,
            SUM(total) as total_spent,
            MAX(order_date) as last_order_date,
            GROUP_CONCAT(DISTINCT contact_number) as contact_numbers,
            GROUP_CONCAT(DISTINCT delivery_address) as delivery_addresses
        FROM orders 
        GROUP BY user_name, user_email
        ORDER BY total_spent DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Customer Orders</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .customer-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .customer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .customer-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .stat-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 1.2em;
            font-weight: bold;
        }
        
        .contact-info {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .view-orders-btn {
            background: #7ab55c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .view-orders-btn:hover {
            background: #689b4a;
        }
        
        .search-section {
            margin-bottom: 20px;
        }
        
        .search-input {
            width: 100%;
            max-width: 300px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
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
                <li onclick="window.location.href='manage_order_details_page.php'">
                    <i class="fas fa-list"></i>
                    <span>Manage Orders</span>
                </li>
                <li class="active">
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
            <h1 class="page-title">Customer Orders</h1>
            
            <div class="search-section">
                <input type="text" id="customerSearch" class="search-input" placeholder="Search customers...">
            </div>
            
            <div class="customer-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="customer-card">
                            <div class="customer-header">
                                <h2><?php echo htmlspecialchars($row['user_name']); ?></h2>
                                <button class="view-orders-btn" onclick="window.location.href='manage_order_details_page.php?user=<?php echo urlencode($row['user_email']); ?>'">
                                    View Orders
                                </button>
                            </div>
                            
                            <div class="customer-stats">
                                <div class="stat-item">
                                    <div class="stat-label">Total Orders</div>
                                    <div class="stat-value"><?php echo $row['total_orders']; ?></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Total Spent</div>
                                    <div class="stat-value">$<?php echo number_format($row['total_spent'], 2); ?></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Last Order</div>
                                    <div class="stat-value"><?php echo date('M d, Y', strtotime($row['last_order_date'])); ?></div>
                                </div>
                            </div>
                            
                            <div class="contact-info">
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['user_email']); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact_numbers']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['delivery_addresses']); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No customers found with orders.</p>";
                }
                ?>
            </div>
        </main>
    </div>

    <script>
        // Customer search functionality
        document.getElementById('customerSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const customerCards = document.querySelectorAll('.customer-card');
            
            customerCards.forEach(card => {
                const customerName = card.querySelector('h2').textContent.toLowerCase();
                const customerEmail = card.querySelector('.contact-info p:first-child').textContent.toLowerCase();
                
                if (customerName.includes(searchTerm) || customerEmail.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 