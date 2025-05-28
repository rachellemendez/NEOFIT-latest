<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$user_email = $_SESSION['email'];
$user_name = '';  // Fetch this from your DB or session as required
$address = '';
$contact = '';

// Always load values for DISPLAY
include 'user_settings_backend.php'; // This should set: $user_name, $address, $contact

// If the user has just saved the profile, clear the form fields for new input
if (isset($_GET['saved'])) {
    $address_input = '';
    $contact_input = '';
} else {
    $address_input = $address;
    $contact_input = $contact;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - User Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Alexandria', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            text-decoration: none;
            letter-spacing: 1px;
        }

        /* Main Content Styles */
        .main-content {
            max-width: 800px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        .settings-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: block;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .welcome-text {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .user-email {
            color: #666;
            font-size: 16px;
        }

        .settings-section {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="tel"]:focus,
        input[type="password"]:focus {
            border-color: #4a90e2;
            outline: none;
        }

        .btn {
            background-color: #4a90e2;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #357abd;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .current-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .current-info p {
            margin: 5px 0;
            color: #666;
        }

        .current-info strong {
            color: #333;
        }

        .logout-section {
            text-align: center;
            margin-top: 40px;
        }

        /* Success Message Styles */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        /* Error Message Styles */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-top: 80px;
            }

            .settings-section {
                padding: 20px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="landing_page.php" class="logo">NEOFIT</a>
        </div>
    </header>

    <main class="main-content">
        <div class="settings-header">
            <img src="profile.jpg" alt="Profile Picture" class="user-avatar">
            <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <p class="user-email"><?php echo htmlspecialchars($user_email); ?></p>
        </div>

        <!-- Success Message -->
        <div class="success-message" id="successMessage">
            Changes saved successfully!
        </div>

        <!-- Error Message -->
        <div class="error-message" id="errorMessage">
            An error occurred. Please try again.
        </div>

        <!-- Profile Information Section -->
        <div class="settings-section">
            <h2 class="section-title">Profile Information</h2>
            <form action="save_profile.php" method="POST" autocomplete="off" id="profileForm">
                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter your delivery address" 
                           value="<?php echo htmlspecialchars($address_input); ?>" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="tel" id="contact" name="contact" placeholder="Enter your contact number (e.g., 09123456789)" 
                           value="<?php echo htmlspecialchars($contact_input); ?>" 
                           pattern="[0-9]{10,11}" title="Please enter a valid contact number (For phone numbers start from '09' only)">
                </div>

                <button type="submit" class="btn">Save Changes</button>

                <div class="current-info">
                    <p><strong>Current Address:</strong> <?php echo htmlspecialchars($address) ?: 'Not set'; ?></p>
                    <p><strong>Current Contact:</strong> <?php echo htmlspecialchars($contact) ?: 'Not set'; ?></p>
                </div>
            </form>
        </div>

        <!-- Password Section -->
        <div class="settings-section">
            <h2 class="section-title">Security Settings</h2>
            <form action="change_password.php" method="POST" id="passwordForm">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn">Update Password</button>
            </form>
        </div>

        <!-- Logout Section -->
        <div class="logout-section">
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle URL parameters for success/error messages
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');

        if (urlParams.has('saved')) {
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }

        if (urlParams.has('error')) {
            errorMessage.style.display = 'block';
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
        }

        // Contact number validation
        const contactInput = document.querySelector('input[name="contact"]');
        contactInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });

        // Password confirmation validation
        const passwordForm = document.getElementById('passwordForm');
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirmation password do not match!');
            }
        });
    });
    </script>
</body>
</html>
