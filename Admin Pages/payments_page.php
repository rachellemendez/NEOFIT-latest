<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Payments</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .payment-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .payment-filters {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
            margin-bottom: 20px;
        }
        .payment-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .summary-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .summary-card .amount {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }
        .payment-details {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .page-link {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
        }
        .page-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'payment_functions.php'; ?>
    
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>
    
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
                <li class="active">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </li>
                <li onclick="window.location.href='neocreds_page.php'">
                    <i class="fas fa-coins"></i>
                    <span>NeoCreds</span>
                </li>
                <li onclick="window.location.href='settings.php'">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <h1 class="page-title">Payments</h1>
            
            <!-- Payment Summary Cards -->
            <div class="payment-summary">
                <div class="summary-card">
                    <h3>Total Revenue</h3>
                    <div class="amount">₱<?php echo number_format(getTotalRevenue(), 2); ?></div>
                </div>
                <div class="summary-card">
                    <h3>Today's Earnings</h3>
                    <div class="amount">₱<?php echo number_format(getTodayEarnings(), 2); ?></div>
                </div>
                <div class="summary-card">
                    <h3>Pending Payments</h3>
                    <div class="amount">₱<?php echo number_format(getPendingPayments(), 2); ?></div>
                </div>
            </div>

            <div class="content-card">
                <!-- Payment Filters -->
                <div class="payment-filters">
                    <input type="text" placeholder="Search by Order ID or Customer" class="search-input" id="search-input">
                    <select class="filter-select" id="status-filter">
                        <option value="">Payment Status</option>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                    <input type="date" class="date-filter" id="date-filter">
                    <button class="btn-apply" id="apply-filters">Apply Filters</button>
                </div>

                <!-- Payments Table -->
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="payments-table-body">
                        <?php
                        $payments = getFilteredPayments();
                        foreach ($payments as $payment) {
                            $statusClass = '';
                            switch($payment['status']) {
                                case 'success':
                                    $statusClass = 'status-success';
                                    break;
                                case 'pending':
                                    $statusClass = 'status-pending';
                                    break;
                                case 'failed':
                                    $statusClass = 'status-failed';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?php echo $payment['transaction_id']; ?></td>
                                <td><?php echo $payment['order_id']; ?></td>
                                <td><?php echo $payment['customer_name']; ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($payment['payment_date'])); ?></td>
                                <td>₱<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo ucfirst($payment['payment_method']); ?></td>
                                <td><span class="payment-status <?php echo $statusClass; ?>"><?php echo ucfirst($payment['status']); ?></span></td>
                                <td>
                                    <span class="payment-details" onclick="viewPaymentDetails('<?php echo $payment['transaction_id']; ?>')">
                                        View Details
                                    </span>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination" id="pagination">
                    <?php
                    $total = getFilteredPaymentsCount();
                    $total_pages = ceil($total / 10);
                    
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = $i == 1 ? 'active' : '';
                        echo "<a href='#' class='page-link $active' data-page='$i'>$i</a>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }

        // Function to update the payments table
        function updatePaymentsTable(data) {
            const tbody = document.getElementById('payments-table-body');
            tbody.innerHTML = '';
            
            data.payments.forEach(payment => {
                let statusClass = '';
                switch(payment.status) {
                    case 'success':
                        statusClass = 'status-success';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        break;
                    case 'failed':
                        statusClass = 'status-failed';
                        break;
                }
                
                tbody.innerHTML += `
                    <tr>
                        <td>${payment.transaction_id}</td>
                        <td>${payment.order_id}</td>
                        <td>${payment.customer_name}</td>
                        <td>${payment.date}</td>
                        <td>₱${payment.amount}</td>
                        <td>${payment.payment_method}</td>
                        <td><span class="payment-status ${statusClass}">${payment.status}</span></td>
                        <td>
                            <span class="payment-details" onclick="viewPaymentDetails('${payment.transaction_id}')">
                                View Details
                            </span>
                        </td>
                    </tr>
                `;
            });
            
            // Update pagination
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            
            for (let i = 1; i <= data.total_pages; i++) {
                const active = i === currentPage ? 'active' : '';
                pagination.innerHTML += `<a href="#" class="page-link ${active}" data-page="${i}">${i}</a>`;
            }
            
            // Add click events to new pagination links
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt(this.dataset.page);
                    loadPayments();
                });
            });
        }

        // Function to load payments with filters
        let currentPage = 1;
        
        function loadPayments() {
            showLoading();
            
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;
            const date = document.getElementById('date-filter').value;
            
            const url = `filter_payments.php?page=${currentPage}&search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&date=${encodeURIComponent(date)}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    updatePaymentsTable(data);
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                });
        }

        // Event listener for filter button
        document.getElementById('apply-filters').addEventListener('click', function() {
            currentPage = 1;
            loadPayments();
        });

        // Event listeners for pagination
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                loadPayments();
            });
        });

        // Function to view payment details
        function viewPaymentDetails(transactionId) {
            // Implement payment details view logic here
            console.log('Viewing details for transaction:', transactionId);
            // You can implement a modal or redirect to a details page
        }
    </script>
</body>
</html> 