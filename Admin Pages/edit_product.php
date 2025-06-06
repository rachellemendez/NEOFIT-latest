<?php

if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    echo "<script>alert('Product updated successfully!');</script>";
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    echo "<script>alert('Product deleted successfully!');</script>";
}

include '../db.php'; // Or however you connect to your DB

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header('Location: all_product_page.php');
    exit;
}

$id = intval($_GET['id']);

// Get product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: all_product_page.php');
    exit;
}

$product = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Edit Product</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-input:focus {
            border-color: #4d8d8b;
            outline: none;
        }

        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .preview-item {
            position: relative;
        }

        .preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #4d8d8b;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            color: #28a745;
            font-size: 14px;
            margin-top: 5px;
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
            <div class="content-card">
                <div class="form-container">
                    <h2>Edit Product</h2>
                    <form id="editProductForm" method="POST" action="update_product.php" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-input" 
                                   value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="product_category" class="form-input" required>
                                <option value="Men" <?php echo $product['product_category'] === 'Men' ? 'selected' : ''; ?>>Men</option>
                                <option value="Women" <?php echo $product['product_category'] === 'Women' ? 'selected' : ''; ?>>Women</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="number" name="product_price" class="form-input" 
                                   value="<?php echo $product['product_price']; ?>" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Stock Quantities</label>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                <div>
                                    <label>Small</label>
                                    <input type="number" name="quantity_small" class="form-input" 
                                           value="<?php echo $product['quantity_small']; ?>" min="0" required>
                                </div>
                                <div>
                                    <label>Medium</label>
                                    <input type="number" name="quantity_medium" class="form-input" 
                                           value="<?php echo $product['quantity_medium']; ?>" min="0" required>
                                </div>
                                <div>
                                    <label>Large</label>
                                    <input type="number" name="quantity_large" class="form-input" 
                                           value="<?php echo $product['quantity_large']; ?>" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="product_status" class="form-input" required>
                                <option value="live" <?php echo $product['product_status'] === 'live' ? 'selected' : ''; ?>>Live</option>
                                <option value="unpublished" <?php echo $product['product_status'] === 'unpublished' ? 'selected' : ''; ?>>Unpublished</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Front Photo</label>
                            <input type="file" name="photo_front" class="form-input" accept="image/*">
                            <div class="image-preview">
                                <?php if (!empty($product['photoFront'])): ?>
                                    <div class="preview-item">
                                        <img src="<?php echo $product['photoFront']; ?>" alt="Front Photo" class="preview-image">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Additional Photos</label>
                            <input type="file" name="photo_1" class="form-input" accept="image/*">
                            <input type="file" name="photo_2" class="form-input" accept="image/*" style="margin-top: 10px;">
                            <input type="file" name="photo_3" class="form-input" accept="image/*" style="margin-top: 10px;">
                            <input type="file" name="photo_4" class="form-input" accept="image/*" style="margin-top: 10px;">
                            <div class="image-preview">
                                <?php
                                $additional_photos = ['photo1', 'photo2', 'photo3', 'photo4'];
                                foreach ($additional_photos as $photo):
                                    if (!empty($product[$photo])):
                                ?>
                                    <div class="preview-item">
                                        <img src="<?php echo $product[$photo]; ?>" alt="Additional Photo" class="preview-image">
                                    </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='all_product_page.php'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Preview uploaded images
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = this.parentElement.querySelector('.image-preview');
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        preview.innerHTML = '';
                        preview.appendChild(img);
                    }.bind(this);
                    reader.readAsDataURL(file);
                }
            });
        });

        // Form validation
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            const price = document.querySelector('input[name="product_price"]').value;
            const small = document.querySelector('input[name="quantity_small"]').value;
            const medium = document.querySelector('input[name="quantity_medium"]').value;
            const large = document.querySelector('input[name="quantity_large"]').value;

            if (price <= 0) {
                e.preventDefault();
                alert('Price must be greater than 0');
                return;
            }

            if (small < 0 || medium < 0 || large < 0) {
                e.preventDefault();
                alert('Stock quantities cannot be negative');
                return;
            }
        });
    </script>
</body>
</html>