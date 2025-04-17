<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Manage Orders</title>
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
                    <span>Dashboard</span>
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
                    <span>Settings</span>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <h1 class="page-title">Manage Orders</h1>
            
            <div class="tabs">
                <div class="tab active">Order List</div>
                <div class="tab">Generated Documents</div>
            </div>
            
            <div class="search-filter">
                <input type="text" placeholder="Search product">
                <input type="text" placeholder="Filter">
                <button class="btn-apply">Apply</button>
                <button class="btn-reset">Reset</button>
            </div>
            
            <div class="product-count">1 product</div>
            
            <div class="content-card">
                <table>
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#10231</td>
                            <td>Maria Santos</td>
                            <td><span class="status delivered">Delivered</span></td>
                            <td>Credit Card</td>
                            <td>Apr 12, 2025</td>
                            <td>$249.99</td>
                            <td>
                                <button class="action-btn view-btn">View</button>
                                <button class="action-btn edit-btn">Edit</button>
                                <button class="action-btn delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#10230</td>
                            <td>John Smith</td>
                            <td><span class="status processing">Processing</span></td>
                            <td>PayPal</td>
                            <td>Apr 11, 2025</td>
                            <td>$129.50</td>
                            <td>
                                <button class="action-btn view-btn">View</button>
                                <button class="action-btn edit-btn">Edit</button>
                                <button class="action-btn delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#10229</td>
                            <td>Alex Johnson</td>
                            <td><span class="status shipped">Shipped</span></td>
                            <td>Apple Pay</td>
                            <td>Apr 10, 2025</td>
                            <td>$450.00</td>
                            <td>
                                <button class="action-btn view-btn">View</button>
                                <button class="action-btn edit-btn">Edit</button>
                                <button class="action-btn delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#10228</td>
                            <td>Emily Chen</td>
                            <td><span class="status cancelled">Cancelled</span></td>
                            <td>Credit Card</td>
                            <td>Apr 09, 2025</td>
                            <td>$75.25</td>
                            <td>
                                <button class="action-btn view-btn">View</button>
                                <button class="action-btn edit-btn">Edit</button>
                                <button class="action-btn delete-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </main>
    </div>
    
    <script>
        // Simple JavaScript for interactivity
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelector('.tab.active').classList.remove('active');
                this.classList.add('active');
            });
        });
        
        document.querySelectorAll('.sidebar-menu li').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelector('.sidebar-menu li.active')?.classList.remove('active');
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>