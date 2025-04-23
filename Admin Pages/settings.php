<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT Admin - Settings</title>
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
        
        <form action="admin_logout.php" method="POST">
            <button><a href="index.php">Logout</a></button>
        </form>
    </div>
</body>
</html>