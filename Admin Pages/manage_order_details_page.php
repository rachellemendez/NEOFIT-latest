<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Order Details</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li>
                    <i class="fas fa-chart-line"></i>
                    <a href="dashboard_page.php"><span>Dashboard</span></a>
                </li>
                <li class="active">
                    <i class="fas fa-list"></i>
                    <span>Manage Orders</span>
                </li>
                <li>
                    <i class="fas fa-box"></i>
                    <span>Inventory</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </li>
                <li>
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </li>
                <li>
                    <i class="fas fa-cog"></i>
                    <a href="settings.php"><span>Settings</span></a>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <h1 class="page-title">Manage Orders</h1>
            
            <div class="order-header">
                <h2>Order #10231</h2>
                <div class="delivery-status">
                    <span class="status-indicator"></span>
                    <span>For Delivery</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            
            <div class="content-card">
                <div class="order-details-row">
                    <div>
                        <strong>Order Date:</strong>
                        <span>04/25/2025</span>
                    </div>
                    <div>
                        <strong>Tracking Number:</strong>
                        <span>0123456789</span>
                    </div>
                </div>
                
                <div class="order-details-row">
                    <div>
                        <strong>Payment Method:</strong>
                        <span>GCash</span>
                    </div>
                    <div>
                        <strong>Total Amount:</strong>
                        <span>Php. 250</span>
                    </div>
                </div>
            </div>
            
            <div class="order-details">
                <div class="customer-info">
                    <div class="info-header">Customer Information</div>
                    <div class="info-content">
                        <p>Maria Santos</p>
                        <p>0912 345 6789</p>
                        <p>mariasantos@gmail.com</p>
                        <p>Blk 3 Lot 4, Village 1, Brgy. Niog, Bacoor City</p>
                    </div>
                </div>
                
                <div class="timeline">
                    <div class="info-header">Order Timeline</div>
                    <div class="info-content">
                        <div class="timeline-item">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div>Order Placed</div>
                                <div>04/04/2025, 2:15 pm</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div>Paid</div>
                                <div>04/04/25, 2:20 pm</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div>Packed</div>
                                <div>04/05/2025, 8:00 am</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-card">
                <div class="info-header">Order Items</div>
                
                <div class="order-items">
                    <div class="item-row">
                        <div class="item-image"></div>
                        <div>Snoopy T-shirt</div>
                        <div>1</div>
                        <div>P 250</div>
                        <div>P 250</div>
                    </div>
                    
                    <div class="summary-row">
                        <div>Shipping Fee</div>
                        <div>P 100</div>
                        <div>P 100</div>
                    </div>
                    
                    <div class="summary-row">
                        <div><strong>Total</strong></div>
                        <div></div>
                        <div><strong>P 350</strong></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>