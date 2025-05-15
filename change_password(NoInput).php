<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Account Deletion</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        /* Header styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
            color: #000;
            letter-spacing: 1px;
        }

        nav {
            margin-left: auto;
            margin-right: 30px;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #000;
            font-weight: 500;
            font-size: 16px;
        }
        .search-cart {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container input {
            padding: 8px 15px;
            border-radius: 20px;
            border: none;
            background-color: #f0f0f0;
            width: 180px;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            color: #666;
        }

        .profile-icon, .cart-icon {
            font-size: 22px;
            color: #333;
        }
          
     /* Main content styles */
     .container {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        /* Sidebar styles */
        .sidebar {
            width: 215px;
            background-color: #4e9498;
            color: white;
            padding: 20px 0;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-menu li.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar-icon {
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        /* Main content area */
        .content {
            flex: 1;
            padding: 30px 40px;
            background-color: #f5f5f5;
        }

        .content h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }

        .deletion-card {
            background-color: #fff;
            border-radius: 0;
            padding: 40px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            height: 400px; /* Match the height shown in image */
            display: flex;
            flex-direction: column;
        }

        .deletion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .deletion-header h2 {
            font-size: 20px;
            font-weight: 500;
            color: #333;
        }

        .delete-btn {
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 0;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .delete-btn:hover {
            background-color: #c62828;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
            }
            
            nav {
                display: none;
            }
            
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 0;
            }
            
            .content {
                padding: 20px;
            }
            
            .deletion-card {
                padding: 20px;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header section -->
    <header>
        <a href="#" class="logo">NEOFIT</a>
        
        <nav>
            <ul>
                <li><a href="#">Trending</a></li>
                <li><a href="#">Men</a></li>
                <li><a href="#">Women</a></li>
            </ul>
        </nav>
        
        <div class="search-cart">
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                <input type="text" placeholder="Search">
            </div>
            
            <svg class="profile-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            </svg>
            
            <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>
    </header>
    
    <!-- Main container -->
    <div class="container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="active">
                    <div class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                    </div>
                    <a href="#">Profile Settings</a>
                </li>
                <li>
                    <div class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                        </svg>
                    </div>
                    <a href="#">Addresses</a>
                </li>
                <li>
                    <div class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                        </svg>
                    </div>
                    <a href="#">Change Password</a>
                </li>
                <li>
                    <div class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </div>
                    <a href="#">Account Deletion</a>
                </li>
            </ul>
        </aside>

        
        <!-- Main content -->
        <div class="content">
            <h1>Account Deletion</h1>
            
            <div class="deletion-card">
                <div class="deletion-header">
                    <h2>Request Account Deletion</h2>
                    <button class="delete-btn">Delete</button>
                </div>
                
                <!-- Empty space below as shown in the image -->
            </div>
        </div>
    </div>
</body>
</html>