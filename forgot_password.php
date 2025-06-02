<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Forgot Password</title>
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
            background-color: rgb(255, 255, 255);
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

        .content-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .forgot-password-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .description {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
            text-align: center;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
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

        .submit-button {
            width: 100%;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-button:hover {
            background-color: #333;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">NEOFIT</div>
        </div>
    </header>

    <div class="content-container">
        <div class="forgot-password-container">
            <h2 class="title">Reset Password</h2>
            <p class="description">Enter your email address and we'll send you instructions to reset your password.</p>
            
            <div id="message" class="message"></div>
            
            <form id="forgot-password-form">
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-input" placeholder="Email address" required>
                </div>
                <button type="submit" class="submit-button">Send Reset Link</button>
            </form>
            
            <a href="index.php" class="back-link">Back to Login</a>
        </div>
    </div>

    <script>
        document.getElementById('forgot-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const messageDiv = document.getElementById('message');
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
            
            // Send request to backend
            fetch('forgot_password_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = `message ${data.status}`;
                messageDiv.style.display = 'block';
                
                if (data.status === 'success') {
                    document.getElementById('email').value = '';
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
                submitButton.textContent = 'Send Reset Link';
            });
        });
    </script>
</body>
</html> 