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
            text-decoration: none;
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
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            padding-right: 45px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
            color: #333;
            height: 42px;
            line-height: 42px;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
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

        #verificationForm, #newPasswordForm {
            display: none;
        }

        .security-question {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .password-requirements {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .requirement {
            margin: 3px 0;
            padding-left: 15px;
            position: relative;
        }

        .requirement.valid {
            color: #28a745;
        }

        .requirement.valid::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #28a745;
        }

        .password-toggle {
            position: absolute;
            right: 2px;
            top: 32px; /* Adjusted for label presence */
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
            background: transparent;
            border: none;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #333;
        }

        .password-toggle i {
            font-size: 18px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">NEOFIT</a>
        </div>
    </header>

    <div class="content-container">
        <div class="forgot-password-container">
            <!-- Email Check Form -->
            <div id="emailCheckForm">
                <h2 class="title">Forgot Password</h2>
                <p class="description">Enter your email address to recover your password.</p>
                
                <form onsubmit="checkEmail(event)">
                    <div class="form-group">
                        <input type="email" id="recovery_email" class="form-input" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="submit-button">Continue</button>
                </form>
            </div>

            <!-- Verification Form -->
            <div id="verificationForm">
                <h2 class="title">Verify Your Identity</h2>
                <p class="description">Please enter your details and answer the security question.</p>
                
                <form onsubmit="verifyDetails(event)">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" id="verify_first_name" class="form-input" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" id="verify_last_name" class="form-input" placeholder="Enter your last name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Security Question:</label>
                        <p id="security_question_text" class="security-question" style="margin: 10px 0; font-size: 16px;"></p>
                        <input type="text" id="security_answer_input" class="form-input" placeholder="Your Answer" required>
                    </div>
                    <button type="submit" class="submit-button">Verify</button>
                </form>
            </div>

            <!-- New Password Form -->
            <div id="newPasswordForm">
                <h2 class="title">Create New Password</h2>
                <p class="description">Please enter your new password and update your security question.</p>
                
                <form onsubmit="updatePassword(event)">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" id="new_password" class="form-input" placeholder="Enter new password" required>
                        <span class="password-toggle" onmousedown="showPassword('new_password')" onmouseup="hidePassword('new_password')" onmouseleave="hidePassword('new_password')"><i class="fa-solid fa-eye-slash"></i></span>
                        <div class="password-requirements">
                            <div class="requirement" id="length">At least 8 characters long</div>
                            <div class="requirement" id="letter">Contains at least one letter</div>
                            <div class="requirement" id="number">Contains at least one number</div>
                            <div class="requirement" id="special">Contains at least one special character</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" class="form-input" placeholder="Confirm new password" required>
                        <span class="password-toggle" onmousedown="showPassword('confirm_password')" onmouseup="hidePassword('confirm_password')" onmouseleave="hidePassword('confirm_password')"><i class="fa-solid fa-eye-slash"></i></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Security Question</label>
                        <select id="new_security_question" class="form-input" required>
                            <option value="fav_food">What is your favorite food?</option>
                            <option value="fav_color">What is your favorite color?</option>
                            <option value="first_pet">What was your first pet's name?</option>
                            <option value="fav_flower">What is your favorite flower?</option>
                            <option value="fav_place">What is your favorite place?</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Security Answer</label>
                        <input type="text" id="new_security_answer" class="form-input" placeholder="Enter your answer" required>
                    </div>
                    <button type="submit" class="submit-button">Update Password</button>
                </form>
            </div>

            <!-- Result Message -->
            <div id="resultMessage" class="message"></div>
            
            <a href="index.php" class="back-link">Back to Login</a>
        </div>
    </div>

    <script>
        let recoveryEmail = '';
        let userId = '';

        function checkEmail(event) {
            event.preventDefault();
            const email = document.getElementById('recovery_email').value;
            recoveryEmail = email;

            const submitButton = event.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Checking...';

            fetch('forgot_password_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=check_email&email=${encodeURIComponent(email)}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('resultMessage');
                if (data.status === 'success') {
                    document.getElementById('emailCheckForm').style.display = 'none';
                    document.getElementById('verificationForm').style.display = 'block';
                    // Clear name fields
                    document.getElementById('verify_first_name').value = '';
                    document.getElementById('verify_last_name').value = '';
                    // Display the security question
                    document.getElementById('security_question_text').textContent = data.data.security_question;
                    messageDiv.style.display = 'none';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const messageDiv = document.getElementById('resultMessage');
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Continue';
            });
        }

        function verifyDetails(event) {
            event.preventDefault();
            const firstName = document.getElementById('verify_first_name').value;
            const lastName = document.getElementById('verify_last_name').value;
            const securityAnswer = document.getElementById('security_answer_input').value;

            const submitButton = event.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Verifying...';

            fetch('forgot_password_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=verify_details&email=${encodeURIComponent(recoveryEmail)}&first_name=${encodeURIComponent(firstName)}&last_name=${encodeURIComponent(lastName)}&security_answer=${encodeURIComponent(securityAnswer)}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('resultMessage');
                if (data.status === 'success') {
                    document.getElementById('verificationForm').style.display = 'none';
                    document.getElementById('newPasswordForm').style.display = 'block';
                    userId = data.data.user_id;
                    messageDiv.style.display = 'none';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const messageDiv = document.getElementById('resultMessage');
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Verify';
            });
        }

        function validatePassword() {
            const password = document.getElementById('new_password').value;
            let isValid = true;

            // Check length
            const lengthReq = document.getElementById('length');
            if(password.length >= 8) {
                lengthReq.classList.add('valid');
            } else {
                lengthReq.classList.remove('valid');
                isValid = false;
            }
            
            // Check for letters
            const letterReq = document.getElementById('letter');
            if(/[a-zA-Z]/.test(password)) {
                letterReq.classList.add('valid');
            } else {
                letterReq.classList.remove('valid');
                isValid = false;
            }
            
            // Check for numbers
            const numberReq = document.getElementById('number');
            if(/[0-9]/.test(password)) {
                numberReq.classList.add('valid');
            } else {
                numberReq.classList.remove('valid');
                isValid = false;
            }
            
            // Check for special characters
            const specialReq = document.getElementById('special');
            if(/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                specialReq.classList.add('valid');
            } else {
                specialReq.classList.remove('valid');
                isValid = false;
            }

            return isValid;
        }

        function updatePassword(event) {
            event.preventDefault();
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const newSecurityQuestion = document.getElementById('new_security_question').value;
            const newSecurityAnswer = document.getElementById('new_security_answer').value;
            const messageDiv = document.getElementById('resultMessage');

            if (!validatePassword()) {
                messageDiv.textContent = 'Please meet all password requirements.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }

            if (newPassword !== confirmPassword) {
                messageDiv.textContent = 'Passwords do not match.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }

            if (!newSecurityQuestion) {
                messageDiv.textContent = 'Please select a security question.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
                return;
            }

            const submitButton = event.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';

            fetch('forgot_password_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_password&email=${encodeURIComponent(recoveryEmail)}&new_password=${encodeURIComponent(newPassword)}&security_question=${encodeURIComponent(newSecurityQuestion)}&security_answer=${encodeURIComponent(newSecurityAnswer)}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('resultMessage');
                if (data.status === 'success') {
                    document.getElementById('newPasswordForm').style.display = 'none';
                    messageDiv.innerHTML = 'Your password and security question have been updated successfully. You can now <a href="index.php">login</a> with your new password.';
                    messageDiv.className = 'message success';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                }
                messageDiv.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Update Password';
            });
        }

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

        // Add password validation on input
        document.getElementById('new_password').addEventListener('input', validatePassword);
    </script>
</body>
</html> 