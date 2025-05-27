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
                                <a href="#" class="forgot-link">Forgot Password?</a>
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
                        <form class="auth-form" method="POST" action="signup_backend.php">
                            <div class="form-group">
                                <input type="text" id="fullname" name="first_name" class="form-input" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input type="text" id="lastname" name="last_name" class="form-input" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input type="email" id="signup-email" name="email" class="form-input" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" id="signup-password" name="password" class="form-input" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="password" id="confirm-password" name="confirm_password" class="form-input" placeholder="Confirm Password">
                            </div>
                            <div class="terms-text">
                                By signing up, I accept NeoFit's <a href="#">Privacy Policy</a> and <a href="#">Legal Statement</a>
                            </div>
                            <button type="submit" name="submit" class="auth-button">Sign Up</button>
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
    </script>
</body>
</html>