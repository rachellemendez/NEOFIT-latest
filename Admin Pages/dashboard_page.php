<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>


        /* Action Icons */
        .action-icons {
            display: flex;
            gap: 10px;
        }

        /* Dashboard Specific Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .stat-card h3 {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 500;
            color: #333;
        }

        .stat-card .trend {
            font-size: 14px;
            color: #7ab55c;
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .trend.up {
            color: #7ab55c;
        }

        .trend.down {
            color: #e74c3c;
        }

        .stat-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 22px;
            color: #4d8d8b;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 300px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-header h3 {
            font-size: 18px;
            color: #333;
        }

        .chart-controls {
            display: flex;
            gap: 10px;
        }

        .period-selector {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f5f5f5;
            cursor: pointer;
        }

        .period-selector.active {
            background-color: #4d8d8b;
            color: white;
            border-color: #4d8d8b;
        }

        .placeholder-chart {
            width: 100%;
            height: 220px;
            background-color: #f9f9f9;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .activity-feed {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .activity-header h3 {
            font-size: 18px;
            color: #333;
        }

        .view-all {
            color: #4d8d8b;
            font-size: 14px;
            cursor: pointer;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background-color: #f2f2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4d8d8b;
        }

        .activity-content {
            flex: 1;
        }

        .activity-content p {
            margin-bottom: 5px;
        }

        .activity-time {
            color: #888;
            font-size: 14px;
        }

        .inventory-items {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .inventory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .inventory-header h3 {
            font-size: 18px;
            color: #333;
        }

        .stock-level {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 5px;
        }

        .in-stock {
            background-color: #d5f5e3;
            color: #27ae60;
        }

        .low-stock {
            background-color: #fdebd0;
            color: #f39c12;
        }

        .out-of-stock {
            background-color: #fadbd8;
            color: #e74c3c;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .order-details {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <h1>NEOFIT</h1>
            <span class="admin-tag">Admin</span>
        </div>
        <div class="user-icon">
            <i class="fas fa-user-circle"></i>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li class="active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </li>
                <li>
                    <i class="fas fa-clipboard-list"></i>
                    <a href="manage_order_details_page.php"><span>Manage Orders</span></a>
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
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Dashboard</h1>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Today's Sales</h3>
                    <div class="value">$8,459</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 12.5% from yesterday
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="value">124</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 5.3% from yesterday
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Average Order Value</h3>
                    <div class="value">$68.22</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 2.1% from yesterday
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Conversion Rate</h3>
                    <div class="value">3.2%</div>
                    <div class="trend down">
                        <i class="fas fa-arrow-down"></i> 0.5% from yesterday
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="chart-row">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Sales Overview</h3>
                        <div class="chart-controls">
                            <div class="period-selector">Day</div>
                            <div class="period-selector active">Week</div>
                            <div class="period-selector">Month</div>
                            <div class="period-selector">Year</div>
                        </div>
                    </div>
                    <div class="placeholder-chart">
                        [Sales Chart Visualization]
                    </div>
                </div>
                
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Top Categories</h3>
                    </div>
                    <div class="placeholder-chart">
                        [Category Pie Chart]
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-feed">
                <div class="activity-header">
                    <h3>Recent Activity</h3>
                    <div class="view-all">View All</div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="activity-content">
                        <p>Order #1234 was placed by John Smith</p>
                        <div class="activity-time">10 minutes ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="activity-content">
                        <p>Refund request for Order #1156 was processed</p>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="activity-content">
                        <p>New customer account created: Emma Wilson</p>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="activity-content">
                        <p>Order #1205 has been shipped</p>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="inventory-items">
                <div class="inventory-header">
                    <h3>Inventory Status</h3>
                    <button class="btn-apply">Manage Inventory</button>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>NEOFIT Running Shoes - Black</td>
                            <td>RS-BLK-001</td>
                            <td>5</td>
                            <td><span class="stock-level low-stock">Low Stock</span></td>
                            <td><button class="btn-track">Restock</button></td>
                        </tr>
                        <tr>
                            <td>NEOFIT Fitness Tracker</td>
                            <td>FT-100</td>
                            <td>0</td>
                            <td><span class="stock-level out-of-stock">Out of Stock</span></td>
                            <td><button class="btn-track">Restock</button></td>
                        </tr>
                        <tr>
                            <td>NEOFIT Compression Leggings</td>
                            <td>CL-BLK-M</td>
                            <td>2</td>
                            <td><span class="stock-level low-stock">Low Stock</span></td>
                            <td><button class="btn-track">Restock</button></td>
                        </tr>
                        <tr>
                            <td>NEOFIT Resistance Bands Set</td>
                            <td>RB-SET-3</td>
                            <td>42</td>
                            <td><span class="stock-level in-stock">In Stock</span></td>
                            <td><button class="btn-track">Restock</button></td>
                        </tr>
                        <tr>
                            <td>NEOFIT Yoga Mat - Blue</td>
                            <td>YM-BLU-001</td>
                            <td>15</td>
                            <td><span class="stock-level in-stock">In Stock</span></td>
                            <td><button class="btn-track">Restock</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>