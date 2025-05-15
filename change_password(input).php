<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* External CSS would normally be in a separate file, including it here for the demo */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f9f9;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-weight: bold;
            font-size: 24px;
            text-decoration: none;
            color: #000;
        }

        .nav {
            display: flex;
            gap: 30px;
        }

        .nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
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
            width: 220px;
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
        .content {
            flex: 1;
            padding: 30px;
        }

        .page-title {
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: bold;
            color: #222;
        }

        .password-form {
            max-width: 600px;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 500;
            color: #333;
        }

        .password-input-container {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            background-color: #f5f5f5;
            border-radius: 5px;
            font-size: 16px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }

        .submit-btn {
            background-color: #000;
            color: white;
            border: none;
            padding: 12px 40px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">NEOFIT</a>
        <nav class="nav">
            <a href="#">Trending</a>
            <a href="#">Men</a>
            <a href="#">Women</a>
        </nav>
        <div class="search-cart">
            <div class="search-container">
                <input type="text" placeholder="Search">
                <i class="fas fa-search search-icon"></i>
            </div>
            <a href="#"><i class="fas fa-user profile-icon"></i></a>
            <a href="#"><i class="fas fa-shopping-cart cart-icon"></i></a>
        </div>
    </header>

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

        <div class="content">
            <h1 class="page-title">Change Password</h1>
            
            <div class="password-form">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <div class="password-input-container">
                        <input type="password" id="new-password" value="***********">
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-input-container">
                        <input type="text" id="confirm-password" value="newpass1234">
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                
                <button class="submit-btn">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to toggle password visibility (for demonstration purposes)
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Toggle the eye icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>