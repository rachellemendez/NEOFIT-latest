<?php
session_start();
include 'db.php';
include 'includes/address_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user's address
$address_data = get_user_address($_SESSION['user_id'], $conn);
$complete_address = $address_data ? get_complete_address($address_data) : 'No address set';

// Get user's contact
$stmt = $conn->prepare("SELECT name, contact FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($user_name, $contact);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - User Addresses</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Internal CSS to avoid needing external file */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Header styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .navigation {
            display: flex;
            gap: 30px;
        }

        .navigation a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 20px;
            padding: 5px 15px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            padding: 8px;
            width: 200px;
            outline: none;
        }

        .user-actions {
            display: flex;
            gap: 20px;
            align-items: center;
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
        /* Main content styles */
        .content {
            flex: 1;
            padding: 30px 40px;
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* Address card styles */
        .address-panel {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .address-card {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .address-card:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .name {
            font-size: 18px;
            font-weight: 500;
        }

        .phone {
            color: #666;
            background-color: #f5f5f5;
            padding: 8px 15px;
            border-radius: 20px;
        }

        .address-details {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-radius: 5px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .address-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: white;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-default {
            background-color: #4e868e;
            color: white;
        }

        .btn-set-default {
            background-color: #000;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            header {
                padding: 15px;
                flex-wrap: wrap;
            }
            
            .navigation {
                gap: 15px;
            }
            
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .content {
                padding: 20px;
            }
            
            .address-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .address-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">NEOFIT</div>
        <nav class="navigation">
            <a href="#">Trending</a>
            <a href="#">Men</a>
            <a href="#">Women</a>
        </nav>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search">
        </div>
        <div class="user-actions">
            <a href="#"><i class="fas fa-user"></i></a>
            <a href="#"><i class="fas fa-shopping-cart"></i></a>
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

        <main class="content">
            <h1>Addresses</h1>
            
            <div class="address-panel">
                <div class="address-card">
                    <div class="address-header">
                        <div class="name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="phone"><?php echo htmlspecialchars($contact); ?></div>
                    </div>
                    <div class="address-details">
                        <?php echo htmlspecialchars($complete_address); ?>
                    </div>
                    <div class="address-actions">
                        <button class="btn btn-edit" onclick="window.location.href='user-settings.php'">Edit</button>
                        <button class="btn btn-default">Default</button>
                    </div>
                </div>

                <div class="address-card">
                    <div class="address-header">
                        <div class="name">Caleb Hills</div>
                        <div class="phone">(+63) 912 345 6789</div>
                    </div>
                    <div class="address-details">
                        45 P. Gomez Street, Brgy. San Agustin III,<br>
                        Dasmariñas City, Cavite, 4114, Philippines
                    </div>
                    <div class="address-actions">
                        <button class="btn btn-edit">Edit</button>
                        <button class="btn btn-set-default">Set as default</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple JavaScript for interaction
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', () => {
                alert('Edit address details');
            });
        });

        document.querySelector('.btn-set-default').addEventListener('click', function() {
            alert('Address set as default');
            this.textContent = 'Default';
            this.className = 'btn btn-default';
            document.querySelector('.btn-default').textContent = 'Set as default';
            document.querySelector('.btn-default').className = 'btn btn-set-default';
        });
    </script>
</body>
</html>