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
                <div class="tab active">All</div>
                <div class="tab">Live</div>
                <div class="tab">Unpublished</div>
            </div>
            
            <div class="search-filter">
                <input type="text" placeholder="Search product">
                <input type="text" placeholder="Filter">
                <button class="btn-apply">Apply</button>
                <button class="btn-reset">Reset</button>
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
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM products";
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
</body>
</html>