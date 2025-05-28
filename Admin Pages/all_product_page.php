<?php

include '../db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - All Products</title>
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
                <li class="active">
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
            <h1 class="page-title">All Products</h1>
            
            <div class="tabs">
                <div class="tab active" data-filter="All">All</div>
                <div class="tab" data-filter="Live">Live</div>
                <div class="tab" data-filter="Unpublished">Unpublished</div>
            </div>

            <div class="search-filter">
                <input type="text" placeholder="Search product" id="searchInput" name="search">
                <select name="filter" id="filter">
                    <option value="productName">Product Name</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                    <option value="totalStocks">Total Stocks</option>
                    <option value="price">Price</option>
                    <option value="totalPrice">Total Price</option>
                </select>
                <button class="btn-apply">Apply</button>
                <button class="btn-reset" type="reset">Reset</button>
            </div>
            
            <div class="product-count" id="productCount">
            <?php
            $count_sql = "SELECT COUNT(*) as total FROM products";
            $count_result = $conn->query($count_sql);

            if ($count_result && $row = $count_result->fetch_assoc()) {
                echo $row['total'] . " product" . ($row['total'] != 1 ? "s" : "");
            } else {
                echo "0 products";
            }
            ?>
            </div>
            
            <div class="content-card">
                <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Small</th>
                        <th>Medium</th>
                        <th>Large</th>
                        <th>Total Stocks</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="productList">
                    <?php
                    $status = isset($_GET['status']) ? $_GET['status'] : 'All';

                    if ($status == 'Live') {
                        $sql = "SELECT * FROM products WHERE product_status = 'Live'";
                    } elseif ($status == 'Unpublished') {
                        $sql = "SELECT * FROM products WHERE product_status = 'Unpublished'";
                    } else {
                        $sql = "SELECT * FROM products";
                    }

                    $result = $conn->query($sql);

                    $total_small = $total_medium = $total_large = $total_price = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $total_stock = $row['quantity_small'] + $row['quantity_medium'] + $row['quantity_large'];
                            $product_total_price = $row['product_price'] * $total_stock;

                            $total_small += $row['quantity_small'];
                            $total_medium += $row['quantity_medium'];
                            $total_large += $row['quantity_large'];
                            $total_price += $product_total_price;

                            echo "<tr data-id='{$row['id']}'>";
                            echo "<td><span class='text'>" . htmlspecialchars($row['product_name']) . "</span></td>";
                            echo "<td><span class='text'>{$row['quantity_small']}</span></td>";
                            echo "<td><span class='text'>{$row['quantity_medium']}</span></td>";
                            echo "<td><span class='text'>{$row['quantity_large']}</span></td>";
                            echo "<td class='total-stock'><span class='text'>{$total_stock}</span></td>";
                            echo "<td><span class='text'>" . number_format($row['product_price'], 2) . "</span></td>";
                            echo "<td class='total-price'><span class='text'>" . number_format($product_total_price, 2) . "</span></td>";
                            echo "<td>
                                <form action='edit_product.php' method='get' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' class='edit-btn'>Edit</button>
                                </form>
                            </td>";
                            echo "</tr>";
                        }

                        $grand_total_stocks = $total_small + $total_medium + $total_large;

                        echo "<tr style='font-weight:bold'>";
                        echo "<td>Total</td>";
                        echo "<td>$total_small</td>";
                        echo "<td>$total_medium</td>";
                        echo "<td>$total_large</td>";
                        echo "<td>$grand_total_stocks</td>";
                        echo "<td>-</td>";
                        echo "<td>" . number_format($total_price, 2) . "</td>";
                        echo "<td></td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='8'>No products found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const applyBtn = document.querySelector('.btn-apply');
        const resetBtn = document.querySelector('.btn-reset');
        const searchInput = document.getElementById('searchInput');
        const filterSelect = document.getElementById('filter');

        function applySearchFilter() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const filter = filterSelect.value;

            let colIndex;
            switch (filter) {
                case 'productName': colIndex = 0; break;
                case 'small': colIndex = 1; break;
                case 'medium': colIndex = 2; break;
                case 'large': colIndex = 3; break;
                case 'totalStocks': colIndex = 4; break;
                case 'price': colIndex = 5; break;
                case 'totalPrice': colIndex = 6; break;
            }

            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cell = row.cells[colIndex];
                if (!cell) return;

                const cellValue = cell.textContent.trim().toLowerCase();

                if (filter === 'productName') {
                    row.style.display = cellValue.includes(searchTerm) ? '' : 'none';
                } else {
                    const cellNumber = parseFloat(cellValue.replace(/,/g, ''));
                    const searchNumber = parseFloat(searchTerm);
                    row.style.display = (cellNumber === searchNumber) ? '' : 'none';
                }
            });
        }

        applyBtn.addEventListener('click', applySearchFilter);

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
        });

        document.querySelectorAll('.tabs .tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const status = tab.dataset.filter;

                // Load products by status
                fetch('get_products.php?status=' + status)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('productList').innerHTML = data;
                        applySearchFilter(); // Reapply any filter after reload
                    });

                // Load updated product count
                fetch('get_product_count.php?status=' + status)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('productCount').textContent = data;
                    });
            });
        });
    </script>
</body>
</html>
