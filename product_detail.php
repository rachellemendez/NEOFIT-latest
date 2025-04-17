<?php
include 'get_product_value.php';


if (isset($product_data)) {
    $product_name = $product_data['product_name'];
    $product_price = $product_data['product_price'];
    $product_quantity = $product_data['product_quantity'];
    $variants = $product_data['variants'];
    $unique_colors = $product_data['unique_colors'];
    
    // Get unique sizes
    $unique_sizes = array_unique(array_map(function($variant) {
        return $variant['product_size'];
    }, $variants));
} else {
    // Optional fallback
    $product_name = 'Product Not Found';
    $product_price = '0.00';
    $product_quantity = '0';
    $variants = [];
    $unique_colors = [];
    $unique_sizes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Remo 98</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #000000;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            width: 100%;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #000000;
        }

        .nav {
            display: flex;
            gap: 30px;
        }

        .nav a {
            text-decoration: none;
            color: #000000;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            border-radius: 20px;
            padding: 8px 15px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 150px;
            color: #000000;
        }

        .search-bar input::placeholder {
            color: #aaa;
        }

        .icon {
            margin-left: 10px;
            color: #000000;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            margin-top: 40px;
            justify-content: center;
        }

        .product-images {
            width: 45%;
            min-width: 300px;
        }

        .main-image {
            width: 100%;
            height: 400px;
            background-color: #f5f5f5;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
        }

        .thumbnail {
            width: 70px;
            height: 70px;
            background-color: #f5f5f5;
            overflow: hidden;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            width: 45%;
            min-width: 300px;
        }

        .product-title {
            font-size: 32px;
            margin-bottom: 10px;
            color: #000000;
        }

        .rating {
            color: #ffc107;
            margin-bottom: 15px;
        }

        .price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .current-price {
            font-size: 28px;
            color: #55a39b;
            font-weight: bold;
        }

        .original-price {
            font-size: 18px;
            color: #999;
            text-decoration: line-through;
        }

        .product-options {
            margin-bottom: 30px;
        }

        .option-group {
            margin-bottom: 20px;
        }

        .option-label {
            font-size: 18px;
            color: #000;
            margin-bottom: 10px;
        }

        select {
            width: 200px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: #000;
            font-size: 18px;
            cursor: pointer;
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .inventory {
            margin-left: 15px;
            color: #666;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .cart-btn {
            padding: 15px 30px;
            background-color: #fff;
            color: #000;
            border: 1px solid #ddd;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .buy-btn {
            padding: 15px 40px;
            background-color: #000;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        .wishlist-btn {
            width: 50px;
            height: 50px;
            background-color: #fff;
            border: 1px solid #ddd;
            color: #ff4d4d;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .size-chart {
            margin-top: 15px;
            color: #55a39b;
            text-decoration: none;
            cursor: pointer;
        }
        
        /* Footer styles */
        footer {
            padding: 40px 20px;
            background-color: #f5f5f5;
            color: #000;
            margin-top: 60px;
            width: 100%;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 10px;
        }

        .footer-column a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-column a:hover {
            color: #000;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .social-icon:hover {
            background-color: #bbb;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 30px auto 0;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 12px;
            color: #999;
        }
        .user-icon, .cart-icon {
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">NEOFIT</div>
            <div class="nav">
                <a href="#">Trending</a>
                <a href="#">Men</a>
                <a href="#">Women</a>
            </div>
            <div class="search-container">
                <div class="search-bar">
                    <span></span>
                    <input type="text" placeholder="Search">
                </div>
                <div class="user-icon"><a href="user-settings.php"> <img src="profile.jpg" alt="Profile Icon" width="24" height="24"></a></div>
                <div class="cart-icon"> <img src="cart.jpg" alt="Cart Icon" width="24" height="24"></div>
            </div>
        </div>

        <div class="product-container">
            <div class="product-images">
                <div class="main-image">
                    <img src="Models Images/6.png" alt="Remo 98 T-shirt">
                </div>
                <div class="thumbnail-container">
                    <div class="thumbnail"><img src="Models Images/6.png" alt="Thumbnail 1"></div>
                    <div class="thumbnail"><img src="Models Images/4.png" alt="Thumbnail 2"></div>
                    <div class="thumbnail"><img src="Models Images/7.png" alt="Thumbnail 3"></div>
                    <div class="thumbnail"><img src="Models Images/5.png" alt="Thumbnail 4"></div>
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title">Remo 98</h1>
                <div class="rating">
                    4.9 ★★★★★
                </div>
                <div class="price">
                    <span class="current-price">₱ 250</span>
                    <span class="original-price">₱ 350</span>
                </div>

                <form action="payment.php" method="POST" id="buyForm">
                <input type="hidden" name="product_name" value="Remo 98">
                <input type="hidden" name="price" id="hidden-price" value="250">

                <div class="product-options">
                    <!-- Size -->
                    <div class="option-group">
                        <div class="option-label">Size</div>
                        <select name="size" id="size">
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                            <option value="xlarge">X-Large</option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="option-group">
                        <div class="option-label">Quantity</div>
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn" id="decrease">-</button>
                            <input type="text" class="quantity-input" name="quantity" id="quantity" value="1">
                            <button type="button" class="quantity-btn" id="increase">+</button>
                            <span class="inventory" id="inventory-count">2345 pieces available</span>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button type="button" class="cart-btn" id="addToCartBtn">
                        <span>🛒</span> Add to Cart
                    </button>
                    <button type="submit" class="buy-btn">Buy Now</button>
                    <button type="button" class="wishlist-btn">♥</button>
                </div>
                <a href="#" class="size-chart">click here to see size chart</a>
                </form>
            </div>
        </div>
    </div>

    <footer>
    <div class="footer-container">
        <div class="footer-column">
            <h3>About Us</h3>
            <ul>
                <li><a href="#">Our Story</a></li>
                <li><a href="#">Mission and Vision</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h3>Shop</h3>
            <ul>
                <li><a href="#">Trending</a></li>
                <li><a href="#">Men</a></li>
                <li><a href="#">Women</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h3>Support</h3>
            <ul>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h3>Legal</h3>
            <ul>
                <li><a href="#">Terms and Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-container">
        <div class="footer-column">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2025 Neofit. All rights reserved.</p>
        </div>
    </div>
</footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const decreaseButton = document.getElementById('decrease');
            const increaseButton = document.getElementById('increase');
            
            // Handle quantity decrease
            decreaseButton.addEventListener('click', function(event) {
                event.preventDefault();
                let current = parseInt(quantityInput.value);
                if (current > 1) {
                    quantityInput.value = current - 1;
                }
            });

            // Handle quantity increase
            increaseButton.addEventListener('click', function(event) {
                event.preventDefault();
                let current = parseInt(quantityInput.value);
                let max = 2345; // Maximum available quantity
                if (current < max) {
                    quantityInput.value = current + 1;
                }
            });
        });
    </script>
</body> 
</html>