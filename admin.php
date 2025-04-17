<?php
include 'admin_backend.php';  // Include the PHP file to connect to the database and fetch data
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NEOFIT Admin Dashboard</title>
        <link rel="stylesheet" href="adminstyle.css">
    </head>
    <body>
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                NEOFIT <span class="admin-tag">Admin</span>
            </div>

            <div class="sidebar-menu">
                <div class="menu-category">Product</div>
                <div class="menu-item active" id="products-menu-item">Neofit Products</div>
                <div class="menu-item" id="add-product-menu-item">Add New Product</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- User Icon -->
            <div class="user-icon">
                👤
            </div>

            <!-- Product List View -->
            <div class="product-list-view" id="product-list-section">
                <h1 class="page-title">Neofit Products</h1>

                <div class="tabs">
                    <button class="tab active" data-view="all">All</button>
                    <button class="tab" data-view="live">Live</button>
                    <button class="tab" data-view="unpublished">Unpublished</button>
                </div>

                <div class="search-filter">
                    <input type="text" id="search-input" class="search-input" placeholder="Search product">
                    <select id="filter-category" class="filter-category">
                        <option value="0">Product Name</option>
                        <option value="6">Status</option>
                        <option value="4">Total Stocks</option>
                        <option value="5">Price</option>
                        <option value="7">Total Price</option> 
                    </select>
                    <button class="btn btn-apply" onclick="applyFilter()">Apply</button>
                    <button class="btn btn-reset" onclick="resetFilter()">Reset</button>
                </div>

                <!-- All Products View -->
                <div class="product-view active" id="all-view">
                    <div class="product-count"><?php echo $result_all->num_rows; ?> products</div>

                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Design</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price (₱)</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_all->num_rows > 0) {
                                while ($row = $result_all->fetch_assoc()) {
                                    $total_price = $row['product_quantity'] * $row['product_price'];
                                    echo "<tr>";
                                    echo "<td>" . $row['product_name'] . "</td>";
                                    echo "<td>" . $row['product_design'] . "</td>";
                                    echo "<td>" . $row['product_color'] . "</td>";
                                    echo "<td>" . $row['product_size'] . "</td>";
                                    echo "<td>" . $row['product_quantity'] . "</td>";
                                    echo "<td>" . number_format($row['product_price'], 2) . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . number_format($total_price, 2) . "</td>";
                                    // Added delete button next to the edit button
                                    echo "<td>
                                        <button class='edit-btn' 
                                            data-id='" . $row["id"] . "' 
                                            data-name='" . htmlspecialchars($row["product_name"], ENT_QUOTES) . "' 
                                            data-design='" . htmlspecialchars($row["product_design"], ENT_QUOTES) . "' 
                                            data-color='" . htmlspecialchars($row["product_color"], ENT_QUOTES) . "' 
                                            data-size='" . htmlspecialchars($row["product_size"], ENT_QUOTES) . "' 
                                            data-quantity='" . $row["product_quantity"] . "' 
                                            data-price='" . $row["product_price"] . "' 
                                            data-status='" . $row["status"] . "'>
                                            Edit
                                        </button>
                                        <form action='delete_product.php' method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='product_id' value='" . $row['id'] . "' />
                                            <button type='submit' class='delete-btn'>Delete</button>
                                        </form>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No products available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

                <!-- Live Products View -->
                <div class="product-view" id="live-view">
                    <div class="product-count"><?php echo $result_live->num_rows; ?> live products</div>

                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Design</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price (₱)</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_live->num_rows > 0) {
                                while ($row = $result_live->fetch_assoc()) {
                                    $total_price = $row['product_quantity'] * $row['product_price'];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['product_design'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['product_color'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['product_size'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . $row['product_quantity'] . "</td>";
                                    echo "<td>" . number_format($row['product_price'], 2) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . number_format($total_price, 2) . "</td>";
                                    echo "<td>
                                        <button class='edit-btn' 
                                            data-id='" . $row["id"] . "' 
                                            data-name='" . htmlspecialchars($row["product_name"], ENT_QUOTES) . "' 
                                            data-design='" . htmlspecialchars($row["product_design"], ENT_QUOTES) . "' 
                                            data-color='" . htmlspecialchars($row["product_color"], ENT_QUOTES) . "' 
                                            data-size='" . htmlspecialchars($row["product_size"], ENT_QUOTES) . "' 
                                            data-quantity='" . $row["product_quantity"] . "' 
                                            data-price='" . $row["product_price"] . "' 
                                            data-status='" . $row["status"] . "'>
                                            Edit
                                        </button>
                                        <form action='delete_product.php' method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='product_id' value='" . $row['id'] . "' />
                                            <button type='submit' class='delete-btn'>Delete</button>
                                        </form>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No products available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

                <!-- Unpublished Products View -->
                <div class="product-view" id="unpublished-view">
                    <div class="product-count"><?php echo $result_unpublished->num_rows; ?> unpublished products</div>

                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Design</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price (₱)</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_unpublished->num_rows > 0) {
                                while ($row = $result_unpublished->fetch_assoc()) {
                                    $total_price = $row['product_quantity'] * $row['product_price'];
                                    echo "<tr>";
                                    echo "<td>" . $row['product_name'] . "</td>";
                                    echo "<td>" . $row['product_design'] . "</td>";
                                    echo "<td>" . $row['product_color'] . "</td>";
                                    echo "<td>" . $row['product_size'] . "</td>";
                                    echo "<td>" . $row['product_quantity'] . "</td>";
                                    echo "<td>" . number_format($row['product_price'], 2) . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . number_format($total_price, 2) . "</td>";
                                    echo "<td>
                                        <button class='edit-btn' 
                                            data-id='" . $row["id"] . "' 
                                            data-name='" . htmlspecialchars($row["product_name"], ENT_QUOTES) . "' 
                                            data-design='" . htmlspecialchars($row["product_design"], ENT_QUOTES) . "' 
                                            data-color='" . htmlspecialchars($row["product_color"], ENT_QUOTES) . "' 
                                            data-size='" . htmlspecialchars($row["product_size"], ENT_QUOTES) . "' 
                                            data-quantity='" . $row["product_quantity"] . "' 
                                            data-price='" . $row["product_price"] . "' 
                                            data-status='" . $row["status"] . "'>
                                            Edit
                                        </button>
                                        <form action='delete_product.php' method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='product_id' value='" . $row['id'] . "' />
                                            <button type='submit' class='delete-btn'>Delete</button>
                                        </form>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No unpublished products available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="nav-controls">
                    <div class="prev-button">←</div>
                    <div class="page-info">Page 1 of 1</div>
                </div>
            </div>

            <!-- Add New Product View (Initially Hidden) -->
            <div id="add-product-section" style="display: none;">
                <form action="admin_backend.php" method="POST">
                    <h1 class="page-title">Add New Product</h1>

                    <div class="product-entry-form">
                        <h2 class="product-entry-title">Product Entry</h2>

                        <div class="form-group">
                        <!-- <div class="form-group">
                            <label class="form-label">Product ID</label>
                            <p class="form-input">Product ID #<?php echo $display_product_id; ?></p>
                        </div> -->

                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-input" name="product-name" id="product-name" placeholder="Product Name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Design</label>
                            <select class="form-select" name="product-design" id="product-design" required>
                                <option value="1">Design 1</option>
                                <option value="2">Design 2</option>
                                <option value="3">Design 3</option>
                                <option value="4">Design 4</option>
                                <option value="5">Design 5</option>
                                <option value="6">Design 6</option>
                                <option value="7">Design 7</option>
                                <option value="8">Design 8</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Color</label>
                            <select class="form-select" name="product-color" id="product-color" required>
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="yellow">Yellow</option>
                                <option value="orange">Orange</option>
                                <option value="purple">Purple</option>
                                <option value="black">Black</option>
                                <option value="white">White</option>
                            </select>
                        </div>

                        <div class="inventory-section">
                            <h3 class="inventory-title">Inventory & Pricing</h3>

                            <div class="form-group">
                                <label class="form-label">Size</label>
                                <select class="form-select" name="product-size" id="product-size" required>
                                    <option value="small">Small</option>
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                    <option value="extra-large">Extra Large</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-input" name="product-quantity" id="product-quantity" placeholder="Quantity" min="1" value="1" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-input" name="product-price" id="product-price" placeholder="Price" min="1" value="1" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="live">Live</option>
                                    <option value="unpublished">Unpublished</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-submit">
                            <button type="submit" name="submit" class="btn-submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- JavaScript for tab switching and other functionality -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching functionality
                const tabs = document.querySelectorAll('.tab');
                const productViews = document.querySelectorAll('.product-view');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        // Remove active class from all tabs
                        tabs.forEach(t => t.classList.remove('active'));
                        
                        // Add active class to clicked tab
                        this.classList.add('active');
                        
                        // Hide all product views
                        productViews.forEach(view => view.classList.remove('active'));
                        
                        // Show corresponding product view
                        const viewId = this.getAttribute('data-view') + '-view';
                        document.getElementById(viewId).classList.add('active');
                    });
                });

                // Menu item functionality
                const productsMenuItem = document.getElementById('products-menu-item');
                const addProductMenuItem = document.getElementById('add-product-menu-item');
                const productListSection = document.getElementById('product-list-section');
                const addProductSection = document.getElementById('add-product-section');

                productsMenuItem.addEventListener('click', function() {
                    productsMenuItem.classList.add('active');
                    addProductMenuItem.classList.remove('active');
                    productListSection.style.display = 'block';
                    addProductSection.style.display = 'none';
                });

                addProductMenuItem.addEventListener('click', function() {
                    addProductMenuItem.classList.add('active');
                    productsMenuItem.classList.remove('active');
                    productListSection.style.display = 'none';
                    addProductSection.style.display = 'block';
                });

                // Edit button functionality
                const editButtons = document.querySelectorAll('.edit-btn');
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Switch to add product form
                        productsMenuItem.classList.remove('active');
                        addProductMenuItem.classList.add('active');
                        productListSection.style.display = 'none';
                        addProductSection.style.display = 'block';
                        
                        // Fill form with product data
                        const productId = this.getAttribute('data-id');
                        const productName = this.getAttribute('data-name');
                        const productDesign = this.getAttribute('data-design');
                        const productColor = this.getAttribute('data-color');
                        const productSize = this.getAttribute('data-size');
                        const productQuantity = this.getAttribute('data-quantity');
                        const productPrice = this.getAttribute('data-price');
                        const productStatus = this.getAttribute('data-status');
                        
                        // Update form title and button text
                        document.querySelector('.page-title').textContent = 'Edit Product';
                        document.querySelector('.btn-submit').textContent = 'Update';
                        
                        // Add hidden input for product ID
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'product-id';
                        hiddenInput.value = productId;
                        document.querySelector('form').appendChild(hiddenInput);
                        
                        // Set form values
                        document.getElementById('product-name').value = productName;
                        
                        // For select elements, find the option with the matching value
                        const designSelect = document.getElementById('product-design');
                        for (let i = 0; i < designSelect.options.length; i++) {
                            if (designSelect.options[i].value === productDesign) {
                                designSelect.selectedIndex = i;
                                break;
                            }
                        }
                        
                        const colorSelect = document.getElementById('product-color');
                        for (let i = 0; i < colorSelect.options.length; i++) {
                            if (colorSelect.options[i].value === productColor) {
                                colorSelect.selectedIndex = i;
                                break;
                            }
                        }
                        
                        const sizeSelect = document.getElementById('product-size');
                        for (let i = 0; i < sizeSelect.options.length; i++) {
                            if (sizeSelect.options[i].value === productSize) {
                                sizeSelect.selectedIndex = i;
                                break;
                            }
                        }
                        
                        document.getElementById('product-quantity').value = productQuantity;
                        document.getElementById('product-price').value = productPrice;
                        
                        const statusSelect = document.querySelector('select[name="status"]');
                        for (let i = 0; i < statusSelect.options.length; i++) {
                            if (statusSelect.options[i].value === productStatus) {
                                statusSelect.selectedIndex = i;
                                break;
                            }
                        }
                    });
                });
            });

            // Filter functions
            function applyFilter() {
                // Get input values
                const searchInput = document.getElementById('search-input').value.toLowerCase();
                const filterCategory = document.getElementById('filter-category').value;
                
                // Apply filter logic here
                // This is a placeholder - you would implement actual filtering based on your needs
                console.log(`Filtering by "${searchInput}" in category ${filterCategory}`);
                
                // Example implementation would find table rows and filter based on content
                const tables = document.querySelectorAll('.product-table');
                tables.forEach(table => {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const cellValue = row.cells[filterCategory].textContent.toLowerCase();
                        if (cellValue.includes(searchInput)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            function resetFilter() {
                // Clear search input
                document.getElementById('search-input').value = '';
                
                // Reset filter category to default
                document.getElementById('filter-category').selectedIndex = 0;
                
                // Show all rows
                const rows = document.querySelectorAll('.product-table tbody tr');
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        </script>
    </body>
</html>