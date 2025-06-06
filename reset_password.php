<?php
require_once 'db_connection.php';
session_start();

// Validate token
$token = $_GET['token'] ?? '';
if (empty($token)) {
    header('Location: index.php');
    exit();
}

try {
    $conn = get_db_connection();
    
    // Check if token exists and is not expired
    $stmt = $conn->prepare("SELECT email, expiry FROM password_resets WHERE token = ? AND used = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: index.php');
        exit();
    }
    
    $row = $result->fetch_assoc();
    if (strtotime($row['expiry']) < time()) {
        header('Location: index.php');
        exit();
    }
    
    $email = $row['email'];
} catch (Exception $e) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOFIT - Reset Password</title>
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

        .reset-password-container {
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

        .password-requirements {
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
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">NEOFIT</div>
        </div>
    </header>

    <div class="content-container">
        <div class="reset-password-container">
            <h2 class="title">Reset Your Password</h2>
            <p class="description">Please enter your new password below.</p>
            
            <div id="message" class="message"></div>
            
            <form id="reset-password-form">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-input" placeholder="New Password" required>
                    <div class="password-requirements" id="password-requirements">
                        <div class="requirement" id="length">At least 8 characters long</div>
                        <div class="requirement" id="letter">Contains at least one letter</div>
                        <div class="requirement" id="number">Contains at least one number</div>
                        <div class="requirement" id="special">Contains at least one special character</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="password" id="confirm-password" name="confirm_password" class="form-input" placeholder="Confirm New Password" required>
                </div>
                
                <button type="submit" class="submit-button">Reset Password</button>
            </form>
        </div>
    </div>

    <script>
        // Password validation
        const passwordInput = document.getElementById('password');
        const requirements = document.getElementById('password-requirements');
        const length = document.getElementById('length');
        const letter = document.getElementById('letter');
        const number = document.getElementById('number');
        const special = document.getElementById('special');

        function validatePassword() {
            const password = passwordInput.value;
            
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

            return document.querySelectorAll('.requirement:not(.valid)').length === 0;
        }

        passwordInput.addEventListener('input', validatePassword);

        // Form submission
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const messageDiv = document.getElementById('message');
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (!validatePassword()) {
                messageDiv.textContent = 'Please meet all password requirements.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }
            
            if (password !== confirmPassword) {
                messageDiv.textContent = 'Passwords do not match.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Resetting...';
            
            // Send request to backend
            fetch('reset_password_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(new FormData(this))
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = `message ${data.status}`;
                messageDiv.style.display = 'block';
                
                if (data.status === 'success') {
                    // Redirect to login page after successful password reset
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
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
                submitButton.textContent = 'Reset Password';
            });
        });
    </script>
</body>
</html> 