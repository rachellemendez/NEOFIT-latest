<?php

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Product added successfully!');</script>";
}

include '../db.php';

// Query to get the distinct box_ids that are already occupied
$occupiedBoxes = $conn->query("SELECT DISTINCT box_id FROM products")->fetch_all(MYSQLI_ASSOC);

// Create an array of occupied box_ids
$occupiedBoxIds = array_map(function($box) {
    return $box['box_id'];
}, $occupiedBoxes);

// Check if a box is occupied in the dropdown
function isBoxOccupied($boxId, $occupiedBoxIds) {
    return in_array($boxId, $occupiedBoxIds);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Add New Product</title>
    <style>
    /* Common styles for NEOFIT Admin Interface */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }

    body {
        background-color: #f2f2f2;
    }

    /* Header Styles */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        background-color: white;
        border-bottom: 1px solid #e5e5e5;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo h1 {
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .admin-tag {
        font-size: 16px;
        color: #333;
        font-weight: normal;
        margin-left: 8px;
    }

    .user-icon {
        font-size: 24px;
        color: #000;
    }

    /* Layout Styles */
    .container {
        display: flex;
        min-height: calc(100vh - 60px);
    }

    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background-color: #4d8d8b;
        color: white;
    }

    .sidebar-menu {
        list-style: none;
    }

    .sidebar-menu li {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        cursor: pointer;
        border-left: 4px solid transparent;
    }

    .sidebar-menu li.active {
        background-color: #3c7c7a;
        border-left: 4px solid #2d6a68;
    }

    .sidebar-menu li:hover {
        background-color: #3c7c7a;
    }

    .sidebar-menu li.settings-active {
        background-color: #26b6b0;
    }

    .sidebar-menu i {
        margin-right: 15px;
        font-size: 18px;
    }

    .dropdown-icon {
        margin-left: auto;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        padding: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 500;
        margin-bottom: 20px;
        color: #333;
    }

    .content-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Tab Navigation */
    .tabs {
        display: flex;
        margin-bottom: 20px;
    }

    .tab {
        padding: 10px 30px;
        cursor: pointer;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
    }

    .tab:first-child {
        border-radius: 4px 0 0 4px;
    }

    .tab:last-child {
        border-radius: 0 4px 4px 0;
    }

    .tab.active {
        background-color: #ababab;
        color: white;
    }

    /* Form Elements */
    .search-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-filter input,
    .search-filter select {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .search-filter input {
        flex: 1;
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-apply {
        background-color: #7ab55c;
        color: white;
    }

    .btn-reset {
        background-color: #e74c3c;
        color: white;
    }

    .btn-edit {
        background-color: #f1c40f;
        color: white;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: white;
    }

    .btn-track {
        background-color: #7ab55c;
        color: white;
    }

    .assign-slot{
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    /* Table Styles */
    .product-count {
        margin-bottom: 15px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #f9f9f9;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    /* Additional styles for the product form */
    .product-entry-form {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .product-entry-title {
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }
    
    .form-input, .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .inventory-section {
        margin-top: 20px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    
    .inventory-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 15px;
        color: #333;
    }
    
    .form-submit {
        margin-top: 20px;
        text-align: right;
    }
    
    .btn-submit {
        background-color: #4d8d8b;
        color: white;
        padding: 12px 30px;
        font-size: 16px;
    }
    
    .btn-submit:hover {
        background-color: #3c7c7a;
    }

    /* View styles */
    .product-view {
        display: none;
    }
    
    .product-view.active {
        display: block;
    }
    
    .nav-controls {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    
    .prev-button, .next-button {
        cursor: pointer;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li id="products-menu-item">
                    <i class="fas fa-tshirt"></i>
                    <a href="all_product_page.php"><span>All Products</span></a>
                </li>
                <li class="active" id="add-product-menu-item">
                    <i class="fas fa-plus-square"></i>
                    <span>Add New Product</span>
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
            <!-- Add New Product View -->
            <div id="add-product-section">
                    <h1 class="page-title">Add New Product</h1>

                    <div class="product-entry-form">
                        <h2 class="product-entry-title">Product Entry</h2>

                        <!-- FORM -->
                        <form action="add_new_product_backend.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-input" name="product_name" id="product-name" placeholder="Product Name" required>
                            </div>

                            <!-- DESIGN -->
                          
                                <div class="form-group">
                                <label class="form-label">Design</label>
                                    <label for="">Photo For Front Display: <input type="file" accept="image/*" name="photo_front"></label><br>
                                    <label for="">Photo 1: <input type="file" accept="image/*" name="photo_1"></label><br>
                                    <label for="">Photo 2: <input type="file" accept="image/*" name="photo_2"></label><br>
                                    <label for="">Photo 3: <input type="file" accept="image/*" name="photo_3"></label><br>
                                    <label for="">Photo 4: <input type="file" accept="image/*" name="photo_4"></label><br>
                            </div>

                            <div class="inventory-section">
                                <h3 class="inventory-title">Inventory & Pricing</h3>

                                <div class="form-group">
                                    <label class="form-label">Size</label>
                                            <label for="" class="form-label">Small: <input type="number" class="form-input" name="quantity_small" id="product-quantity" placeholder="Quantity" min="1" value="1" required></label><br>
                                            <label for="" class="form-label">Medium: <input type="number" class="form-input" name="quantity_medium" id="product-quantity" placeholder="Quantity" min="1" value="1" required></label><br>
                                            <label for="" class="form-label">Large: <input type="number" class="form-input" name="quantity_large" id="product-quantity" placeholder="Quantity" min="1" value="1" required></label><br>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Price</label>
                                    <input type="number" class="form-input" name="product_price" id="product-price" placeholder="Price" min="1" value="1" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="product_status" id="product_status" required>
                                        <option value="live">Live</option>
                                        <option value="unpublished">Unpublished</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <h6>Assign Slot</h6>
                                <select name="box_id" required>
                                    <option value="">-- Do not assign a box --</option>

                                    <optgroup label="ALL PRODUCTS">
                                    <?php
                                        for ($i = 1; $i <= 8; $i++) {
                                            $disabled = isBoxOccupied($i, $occupiedBoxIds) ? 'disabled' : '';
                                            $label = isBoxOccupied($i, $occupiedBoxIds) ? '(Occupied)' : '';
                                            echo "<option value='$i' $disabled>Box $i $label</option>";
                                        }
                                    ?>
                                    </optgroup>

                                    <optgroup label="TRENDING">
                                    <?php
                                        for ($i = 9; $i <= 16; $i++) {
                                            $disabled = isBoxOccupied($i, $occupiedBoxIds) ? 'disabled' : '';
                                            $label = isBoxOccupied($i, $occupiedBoxIds) ? '(Occupied)' : '';
                                            echo "<option value='$i' $disabled>Box $i $label</option>";
                                        }
                                    ?>
                                    </optgroup>

                                    <optgroup label="MEN">
                                    <?php
                                        for ($i = 17; $i <= 24; $i++) {
                                            $disabled = isBoxOccupied($i, $occupiedBoxIds) ? 'disabled' : '';
                                            $label = isBoxOccupied($i, $occupiedBoxIds) ? '(Occupied)' : '';
                                            echo "<option value='$i' $disabled>Box $i $label</option>";
                                        }
                                    ?>
                                    </optgroup>

                                    <optgroup label="WOMEN">
                                    <?php
                                        for ($i = 25; $i <= 32; $i++) {
                                            $disabled = isBoxOccupied($i, $occupiedBoxIds) ? 'disabled' : '';
                                            $label = isBoxOccupied($i, $occupiedBoxIds) ? '(Occupied)' : '';
                                            echo "<option value='$i' $disabled>Box $i $label</option>";
                                        }
                                    ?>
                                    </optgroup>
                                </select>

                            

                                <div class="form-submit">
                                    <button type="submit" name="product_submit" class="btn-submit" id="product-save-btn">Save</button>
                                </div>
                        </form>
                    </div>
            </div>

</body>
</html>
            
           
    
    