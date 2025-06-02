<?php
session_start();

if(isset($_SESSION['email'])){
    header('Location: landing_page.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Login & Signup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color:rgb(255, 255, 255);
            color: #fff;
        }

        /* Header Styles */
        header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 0;
        }

        .content-container {
            display: flex;
            min-height: calc(100vh - 120px);
            padding: 20px;
            justify-content: center;
            align-items: center;
        }

        .image-container {
            flex: 1;
            max-width: 500%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Single Image Style */
        .feature-image {
            width: 100%;
            max-width: 1000px;
            max-height: 800px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .auth-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            margin: 20px;
            overflow: hidden;
            height: auto;
            min-height: 620px;
            margin-top: -150px; 
            
        }

        .auth-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            color: #999;
            transition: all 0.3s;
            position: relative;
        }

        .tab-btn.active {
            color: #333;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #333;
        }

        .tab-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding-bottom: 20px;
        }

        .tab-content {
            display: none;
            height: 100%;
        }

        .tab-content.active {
            display: flex;
            flex-direction: column;
        }

        .welcome-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }

        .form-group {
            position: relative;
            margin-bottom: -5px;
            min-height: 60px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
        }

        .forgot-link {
            font-size: 12px;
            color: #666;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .terms-text {
            font-size: 12px;
            color: #666;
            margin: 15px 0;
            line-height: 1.5;
        }

        .terms-text a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        .auth-button {
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .auth-button:hover {
            background-color: #333;
        }

        /* Footer */
        footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .footer-links a {
            font-size: 14px;
            color: #000000;
            text-decoration: none;
        }

        .footer-links a:hover {
            color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-container {
                flex-direction: column;
                height: auto;
                padding: 10px;
            }
            
            .image-container {
                max-width: 100%;
                height: auto;
                padding: 10px;
                margin-bottom: 20px;
            }
            
            .feature-image {
                height: 350px;
            }
            
            .auth-container {
                width: 100%;
                max-width: 400px;
                padding: 30px 20px;
                margin: 0;
                height: 580px;
            }
        }

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

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message.pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .input-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">NEOFIT</div>
        </div>
    </header>

    <main>
        <div class="content-container">
            <div class="image-container">
                <!-- Single image instead of slider -->
                <img class="feature-image" src="Login Images/lgfront.png" alt="Fashion models in NEOFIT clothing">
            </div>
            
            <div class="auth-container">
                <h2 class="auth-title">NEOFIT ACCOUNT</h2>
                
                <div class="tabs">
                    <button class="tab-btn active" data-tab="login">Login</button>
                    <button class="tab-btn" data-tab="signup">Sign Up</button>
                </div>
                
                <div class="tab-content-wrapper">
                    <!-- LOGIN FORM -->
                    <div class="tab-content active" id="login-tab">
                        <p class="welcome-text">Welcome back! Log in using your email and password.</p>
                        <form class="auth-form" method="post" action="login_backend.php">
                            <div class="form-group">
                                <input type="email" id="email" name="email" class="form-input" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" name="password" class="form-input" placeholder="Password">
                            </div>
                            <div class="form-footer">
                                <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
                            </div>
                            <div class="terms-text">
                                By signing up, I accept NeoFit's <a href="#">Privacy Policy</a> and <a href="#">Legal Statement</a>
                            </div>
                            <button type="submit" class="auth-button" name="login_submit">Login</button>
                        </form>
                    </div>
                    <!-- END OF LOGIN FORM -->
                    
                    <!-- SIGNUP FORM -->
                    <div class="tab-content" id="signup-tab">
                        <p class="welcome-text">Create your NeoFit account to get started.</p>
                        <form class="auth-form" id="signup-form" method="POST" action="signup_backend.php">
                            <div class="form-group">
                                <input type="text" id="first_name" name="first_name" class="form-input" placeholder="First Name" 
                                    pattern="[A-Za-z ]+" title="First name can only contain letters and spaces" required>
                                <div class="input-error" id="first-name-error"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" id="last_name" name="last_name" class="form-input" placeholder="Last Name" 
                                    pattern="[A-Za-z ]+" title="Last name can only contain letters and spaces" required>
                                <div class="input-error" id="last-name-error"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" id="signup-email" name="email" class="form-input" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="signup-password" name="password" class="form-input" placeholder="Password" required>
                                <div class="password-requirements" id="password-requirements">
                                    <div class="requirement" id="length">At least 8 characters long</div>
                                    <div class="requirement" id="letter">Contains at least one letter</div>
                                    <div class="requirement" id="number">Contains at least one number</div>
                                    <div class="requirement" id="special">Contains at least one special character</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" id="confirm-password" name="confirm_password" class="form-input" placeholder="Confirm Password" required>
                            </div>
                            <div id="signup-message" class="message" style="display: none;"></div>
                            <div class="terms-text">
                                By signing up, I accept NeoFit's <a href="#">Privacy Policy</a> and <a href="#">Legal Statement</a>
                            </div>
                            <input type="hidden" name="signup_submit" value="1">
                            <button type="submit" class="auth-button">Sign Up</button>
                        </form>
                    </div>
                    <!-- END OF SIGNUP FORM -->
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-links">
            <a href="#">Contact Us</a>
            <a href="#">Find a Store</a>
            <a href="#">About NEOFIT</a>
        </div>
    </footer>

    <script>
        // Tab switching functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all tabs
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                btn.classList.add('active');
                const tabId = btn.getAttribute('data-tab');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });

        // Password validation
        const passwordInput = document.getElementById('signup-password');
        const requirements = document.getElementById('password-requirements');
        const length = document.getElementById('length');
        const letter = document.getElementById('letter');
        const number = document.getElementById('number');
        const special = document.getElementById('special');

        // Show password requirements when password field is focused
        passwordInput.addEventListener('focus', function() {
            requirements.style.display = 'block';
        });

        // Hide password requirements when password field loses focus
        passwordInput.addEventListener('blur', function() {
            requirements.style.display = 'none';
        });

        // Check password requirements in real-time
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            // Check length
            if(password.length >= 8) {
                length.classList.add('valid');
            } else {
                length.classList.remove('valid');
            }
            
            // Check for letters
            if(/[a-zA-Z]/.test(password)) {
                letter.classList.add('valid');
            } else {
                letter.classList.remove('valid');
            }
            
            // Check for numbers
            if(/[0-9]/.test(password)) {
                number.classList.add('valid');
            } else {
                number.classList.remove('valid');
            }
            
            // Check for special characters
            if(/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                special.classList.add('valid');
            } else {
                special.classList.remove('valid');
            }
        });

        // Name validation
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const firstNameError = document.getElementById('first-name-error');
        const lastNameError = document.getElementById('last-name-error');

        function validateName(input, errorDiv) {
            const nameRegex = /^[A-Za-z ]+$/;
            const value = input.value.trim();
            
            if (!nameRegex.test(value) && value !== '') {
                errorDiv.textContent = input.title;
                errorDiv.style.display = 'block';
                input.setCustomValidity(input.title);
            } else {
                errorDiv.style.display = 'none';
                input.setCustomValidity('');
            }
        }

        firstNameInput.addEventListener('input', function() {
            validateName(this, firstNameError);
        });

        lastNameInput.addEventListener('input', function() {
            validateName(this, lastNameError);
        });

        // Handle signup form submission
        document.getElementById('signup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageDiv = document.getElementById('signup-message');
            const submitButton = this.querySelector('button[type="submit"]');
            const password = document.getElementById('signup-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validate names
            const firstNameValid = /^[A-Za-z ]+$/.test(firstNameInput.value.trim());
            const lastNameValid = /^[A-Za-z ]+$/.test(lastNameInput.value.trim());

            if (!firstNameValid || !lastNameValid) {
                messageDiv.textContent = 'Names can only contain letters and spaces.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Check if passwords match
            if (password !== confirmPassword) {
                messageDiv.textContent = 'Passwords do not match.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Check if all password requirements are met
            const requirements = document.querySelectorAll('.requirement');
            let allValid = true;
            requirements.forEach(req => {
                if (!req.classList.contains('valid')) {
                    allValid = false;
                }
            });
            
            if (!allValid) {
                messageDiv.textContent = 'Please meet all password requirements.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Signing up...';
            
            // Send form data
            fetch('signup_backend.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = `message ${data.status}`;
                messageDiv.style.display = 'block';
                
                if (data.status === 'success') {
                    // Clear form
                    this.reset();
                    requirements.forEach(req => req.classList.remove('valid'));
                    
                    // Show success message briefly before redirect
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            })
            .catch(error => {
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Sign Up';
            });
        });
    </script>
</body>
</html>