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
                <li>
                    <i class="fas fa-chart-line"></i>
                    <a href="dashboard_page.php"><span>Dashboard</span></a>
                </li>
                <li>
                    <i class="fas fa-list"></i>
                    <a href="manage_order_details_page.php"><span>Manage Orders</span></a>
                </li>
                <li>
                    <i class="fas fa-box"></i>
                    <span>Inventory</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </li>
                <li class="active">
                    <i class="fas fa-tshirt"></i>
                    <span>All Products</span>
                </li>
                <li>
                    <i class="fas fa-plus-square"></i>
                    <a href="add_new_product_page.php"><span>Add New Product</span></a>
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
            
            <div class="product-count">
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
                        </tr>
                    </thead>
                    <tbody id="productList">
                    <?php
                    $status = isset($_GET['status']) ? $_GET['status'] : 'All';  // Get the status from the URL, default to 'All'

                    if ($status == 'Live') {
                        $sql = "SELECT * FROM products WHERE product_status = 'Live'";
                    } elseif ($status == 'Unpublished') {
                        $sql = "SELECT * FROM products WHERE product_status = 'Unpublished'";
                    } else {
                        $sql = "SELECT * FROM products";  // Get all products if status is 'All' or not specified
                    }

                    $result = $conn->query($sql);

                    $total_small = $total_medium = $total_large = $total_price = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $total_stock = $row['quantity_small'] + $row['quantity_medium'] + $row['quantity_large'];
                            $product_total_price = $row['product_price'] * $total_stock;

                            // Add to running totals
                            $total_small += $row['quantity_small'];
                            $total_medium += $row['quantity_medium'];
                            $total_large += $row['quantity_large'];
                            $total_price += $product_total_price;

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                            echo "<td>" . $row['quantity_small'] . "</td>";
                            echo "<td>" . $row['quantity_medium'] . "</td>";
                            echo "<td>" . $row['quantity_large'] . "</td>";
                            echo "<td>" . $total_stock . "</td>";
                            echo "<td>" . number_format($row['product_price'], 2) . "</td>";
                            echo "<td>" . number_format($product_total_price, 2) . "</td>";
                            echo "</tr>";
                        }

                        // Display totals row
                        $grand_total_stocks = $total_small + $total_medium + $total_large;

                        echo "<tr style='font-weight:bold'>";
                        echo "<td>Total</td>";
                        echo "<td>$total_small</td>";
                        echo "<td>$total_medium</td>";
                        echo "<td>$total_large</td>";
                        echo "<td>$grand_total_stocks</td>";
                        echo "<td>-</td>";
                        echo "<td>" . number_format($total_price, 2) . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='7'>No products found.</td></tr>";
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
        const rows = document.querySelectorAll('tbody tr'); // Declare it once

        applyBtn.addEventListener('click', () => {
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
        });

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            rows.forEach(row => row.style.display = '');
        });

        document.querySelectorAll('.tabs .tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Toggle active class
                document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const status = tab.dataset.filter;

                // Make an AJAX request to get the filtered products
                fetch('get_products.php?status=' + status)  // Fetch the new content from the PHP file
                    .then(response => response.text())
                    .then(data => {
                        // Update the product list with the filtered products
                        document.getElementById('productList').innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

    </script>


</body>
</html>