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
                    <span>Dashboard</span>
                </li>
                <li>
                    <i class="fas fa-list"></i>
                    <span>Manage Orders</span>
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
                    <span>Add New Product</span>
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
            
            <div class="product-count">1 product</div>
            
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
                        <tr>
                            <td>Snoopy T-shirt</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>3000</td>
                            <td>250</td>
                            <td>750,000</td>
                        </tr>
                        <tr>
                            <td>Shirt A</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>3000</td>
                            <td>250</td>
                            <td>750,000</td>
                        </tr>
                        <tr>
                            <td>Shirt B</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>3000</td>
                            <td>250</td>
                            <td>750,000</td>
                        </tr>
                        <tr>
                            <td>Shirt C</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>1000</td>
                            <td>3000</td>
                            <td>250</td>
                            <td>750,000</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>4000</td>
                            <td>4000</td>
                            <td>4000</td>
                            <td>12000</td>
                            <td>1000</td>
                            <td>3,000,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>