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
            position: relative;
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
            padding: 12px 35px 12px 12px;
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

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 45px;
            transform: none;
            cursor: pointer;
            color: #666;
            font-size: 14px;
            padding: 5px;
            z-index: 2;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            display: flex;
            align-items: center;
            height: 20px;
        }

        .password-toggle:hover {
            color: #333;
        }

        .password-toggle i {
            font-size: 14px;
            font-style: normal;
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

        /* Add password requirements styles */
        .password-requirements {
            display: none;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .requirement {
            margin: 3px 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .requirement.valid {
            color: #28a745;
        }

        .requirement.invalid {
            color: #dc3545;
        }

        .requirement::before {
            content: '✕';
            color: #dc3545;
        }

        .requirement.valid::before {
            content: '✓';
            color: #28a745;
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

        /* Add NeoCreds Wallet styles */
        .wallet-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .balance-display {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .balance-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Add Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            margin: 15% auto;
            padding: 0;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .close-modal {
            font-size: 24px;
            font-weight: bold;
            color: #666;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-body {
            padding: 20px;
        }

        .preset-amounts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .amount-btn {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .amount-btn:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
            color: #007bff;
        }

        .amount-btn.selected {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .custom-amount {
            margin-bottom: 20px;
        }

        .custom-amount label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .custom-amount input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
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

        <!-- NeoCreds Wallet Section -->
        <div class="settings-section">
            <h2 class="section-title">NeoCreds Wallet</h2>
            <div class="wallet-container">
                <div class="balance-display">
                    <span class="balance-label">Current Balance</span>
                    <span class="balance-amount">₱0.00</span>
                </div>
                <button type="button" class="btn btn-primary" id="addCreditsBtn">Add Credits</button>
            </div>
        </div>

        <!-- Add Credits Modal -->
        <div class="modal" id="addCreditsModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add NeoCreds</h2>
                    <span class="close-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="preset-amounts">
                        <button class="amount-btn" data-amount="50">₱50</button>
                        <button class="amount-btn" data-amount="100">₱100</button>
                        <button class="amount-btn" data-amount="200">₱200</button>
                        <button class="amount-btn" data-amount="500">₱500</button>
                        <button class="amount-btn" data-amount="1000">₱1,000</button>
                        <button class="amount-btn" data-amount="1500">₱1,500</button>
                    </div>
                    <div class="custom-amount">
                        <label for="customAmount">Custom Amount (₱)</label>
                        <input type="number" id="customAmount" min="1" step="1" placeholder="Enter amount">
                    </div>
                    <div class="modal-actions">
                        <button class="btn btn-primary" id="confirmAddCredits">Add Credits</button>
                        <button class="btn btn-secondary" id="requestCredits">Request Credits</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="settings-section">
            <h2 class="section-title">Security Settings</h2>
            <form action="change_password.php" method="POST" id="passwordForm">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <span class="password-toggle" onmousedown="showPassword('current_password')" onmouseup="hidePassword('current_password')" onmouseleave="hidePassword('current_password')"><i class="fa-solid fa-eye-slash"></i></span>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <span class="password-toggle" onmousedown="showPassword('new_password')" onmouseup="hidePassword('new_password')" onmouseleave="hidePassword('new_password')"><i class="fa-solid fa-eye-slash"></i></span>
                    <div class="password-requirements" id="password-requirements">
                        <div class="requirement" id="length">At least 8 characters long</div>
                        <div class="requirement" id="letter">Contains at least one letter</div>
                        <div class="requirement" id="number">Contains at least one number</div>
                        <div class="requirement" id="special">Contains at least one special character</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="password-toggle" onmousedown="showPassword('confirm_password')" onmouseup="hidePassword('confirm_password')" onmouseleave="hidePassword('confirm_password')"><i class="fa-solid fa-eye-slash"></i></span>
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
            // URL parameters for success/error messages
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
            if (contactInput) {
                contactInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                });
            }

            // Password confirmation validation
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;

                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('New password and confirmation password do not match!');
                    }
                });
            }

            // Add Credits Modal Functionality
            const modal = document.getElementById('addCreditsModal');
            const addCreditsBtn = document.getElementById('addCreditsBtn');
            const closeModal = document.querySelector('.close-modal');
            const amountBtns = document.querySelectorAll('.amount-btn');
            const customAmountInput = document.getElementById('customAmount');
            const confirmAddCreditsBtn = document.getElementById('confirmAddCredits');
            const requestCreditsBtn = document.getElementById('requestCredits');

            // Open modal
            if (addCreditsBtn) {
                addCreditsBtn.onclick = function() {
                    modal.style.display = 'block';
                };
            }

            // Close modal
            if (closeModal) {
                closeModal.onclick = function() {
                    modal.style.display = 'none';
                };
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            // Handle preset amount buttons
            amountBtns.forEach(btn => {
                btn.onclick = function() {
                    amountBtns.forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    customAmountInput.value = '';
                };
            });

            // Clear selected buttons when custom amount is entered
            if (customAmountInput) {
                customAmountInput.oninput = function() {
                    amountBtns.forEach(btn => btn.classList.remove('selected'));
                };
            }

            // Handle Add Credits button click
            if (confirmAddCreditsBtn) {
                confirmAddCreditsBtn.onclick = function() {
                    let amount = customAmountInput.value;
                    if (!amount) {
                        const selectedBtn = document.querySelector('.amount-btn.selected');
                        if (selectedBtn) {
                            amount = selectedBtn.dataset.amount;
                        }
                    }
                    
                    if (amount) {
                        // Here you can add the logic to process the payment
                        alert('Processing payment for ₱' + amount);
                        // Temporarily just close the modal
                        modal.style.display = 'none';
                    } else {
                        alert('Please select an amount or enter a custom amount');
                    }
                };
            }

            // Handle Request Credits button click
            if (requestCreditsBtn) {
                requestCreditsBtn.onclick = function() {
                    alert('Credit request feature coming soon!');
                    modal.style.display = 'none';
                };
            }
        });

        // Password toggle functions
        function showPassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            input.type = 'text';
            toggle.innerHTML = '<i class="fa-solid fa-eye"></i>';
        }

        function hidePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            input.type = 'password';
            toggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
        }

        // Make these functions globally available
        window.showPassword = showPassword;
        window.hidePassword = hidePassword;
    </script>
</body>
</html>
