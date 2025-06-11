<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="header-container">
        <a class="logo" href="landing_page.php">NEOFIT</a>        <nav>
            <ul>
                <li><a href="landing_page.php#trending-section" class="nav-link" data-category="trending">All Products</a></li>
                <li><a href="landing_page.php#men-section" class="nav-link" data-category="men">Men</a></li>
                <li><a href="landing_page.php#women-section" class="nav-link" data-category="women">Women</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <div class="user-icon"><a href="user-settings.php"> <img src="profile.jpg" alt="Profile Icon" width="24" height="24"></a></div>
            <div class="cart-icon">
                <a href="cart.php">
                    <img src="cart.jpg" alt="Cart Icon" width="24" height="24">
                    <span class="cart-count">0</span>
                </a>
            </div>
            <div class="shopping-bag-icon">
                <a href="orders.php">
                    <img src="shopping-bag.png" alt="Shopping Bag Icon" width="24" height="24">
                </a>
            </div>
            <div class="favorites-icon">
                <a href="favorites.php">
                    <img src="favorites.png" alt="Favorites Icon" width="24" height="24">
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: #FFFFFF;
        color: #000;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #000000;
        text-decoration: none;
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 25px;
        margin: 0;
        padding: 0;
    }

    nav a {
        text-decoration: none;
        color: #1E1E1E;
        font-size: 14px;
        transition: color 0.3s;
    }

    nav a:hover {
        color: #666;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-icon, .cart-icon, .shopping-bag-icon, .favorites-icon {
        font-size: 18px;
        cursor: pointer;
        position: relative;
    }

    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #55a39b;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .nav-link {
        position: relative;
        transition: color 0.3s;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: #000;
        transition: width 0.3s;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    @media (max-width: 768px) {
        nav ul {
            gap: 15px;
        }
        
        .header-right {
            gap: 15px;
        }
    }
</style>