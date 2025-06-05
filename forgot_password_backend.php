<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

function getSecurityQuestion($value) {
    $questions = [
        'fav_food' => 'What is your favorite food?',
        'fav_color' => 'What is your favorite color?',
        'first_pet' => 'What was your first pet\'s name?',
        'fav_flower' => 'What is your favorite flower?',
        'fav_place' => 'What is your favorite place?'
    ];
    return $questions[$value] ?? $value;
}

function generateRandomPassword() {
    // Define character sets
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $special = '!@#$%^&*()';
    
    // Initialize password with one character from each set
    $password = $lowercase[rand(0, strlen($lowercase) - 1)]; // lowercase
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)]; // uppercase
    $password .= $numbers[rand(0, strlen($numbers) - 1)]; // number
    $password .= $special[rand(0, strlen($special) - 1)]; // special
    
    // Create a pool of all allowed characters
    $all_chars = $lowercase . $uppercase . $numbers . $special;
    
    // Add random characters until we reach desired length (12)
    while(strlen($password) < 12) {
        $password .= $all_chars[rand(0, strlen($all_chars) - 1)];
    }
    
    // Shuffle the password to make it more random
    $password_array = str_split($password);
    shuffle($password_array);
    
    // Return the final password
    return implode('', $password_array);
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Check email existence
    if ($action === 'check_email') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        // Debug log
        error_log("Checking email: " . $email);
        
        $query = "SELECT security_question FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            error_log("MySQL Error: " . mysqli_error($conn));
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error occurred'
            ]);
            exit();
        }
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $actualQuestion = getSecurityQuestion($user['security_question']);
            error_log("User found: " . print_r($user, true));
            echo json_encode([
                'status' => 'success',
                'message' => 'Account found',
                'data' => [
                    'security_question' => $actualQuestion
                ]
            ]);
        } else {
            error_log("No user found with email: " . $email);
            echo json_encode([
                'status' => 'error',
                'message' => 'There is no existing account under this email'
            ]);
        }
    }
    
    // Verify user details and security answer
    else if ($action === 'verify_details') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);
        
        // Debug log
        error_log("Verifying details for email: " . $email);
        
        // First check if user exists and get their security answer
        $query = "SELECT id, security_answer FROM users 
                 WHERE email = '$email' 
                 AND first_name = '$first_name' 
                 AND last_name = '$last_name'";
        
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            error_log("MySQL Error: " . mysqli_error($conn));
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error occurred'
            ]);
            exit();
        }
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Case-sensitive comparison of security answer
            if ($security_answer === $user['security_answer']) {
                error_log("Verification successful for email: " . $email);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Verification successful',
                    'data' => [
                        'user_id' => $user['id']
                    ]
                ]);
            } else {
                error_log("Security answer mismatch for email: " . $email);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'The security answer does not match our records. Please try again.'
                ]);
            }
        } else {
            error_log("User details verification failed for email: " . $email);
            echo json_encode([
                'status' => 'error',
                'message' => 'The provided information does not match our records'
            ]);
        }
    }
    
    // Update password and security question
    else if ($action === 'update_password') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $new_password = $_POST['new_password'];
        $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
        $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);
        
        // Validate password
        if (strlen($new_password) < 8 || 
            !preg_match('/[A-Za-z]/', $new_password) || 
            !preg_match('/[0-9]/', $new_password) || 
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password does not meet requirements'
            ]);
            exit();
        }

        // Check if the new password matches the current password
        $query = "SELECT password FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($new_password, $user['password'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'The new password cannot be the same as your current password. Please choose a different password.'
                ]);
                exit();
            }
        }
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the password and security question in the database
        $query = "UPDATE users SET 
                 password = '$hashed_password',
                 security_question = '$security_question',
                 security_answer = '$security_answer'
                 WHERE email = '$email'";

        if (mysqli_query($conn, $query)) {
            error_log("Password and security question updated successfully for email: " . $email);
            echo json_encode([
                'status' => 'success',
                'message' => 'Password and security question updated successfully'
            ]);
        } else {
            error_log("Error updating password and security question: " . mysqli_error($conn));
            echo json_encode([
                'status' => 'error',
                'message' => 'Error updating password and security question'
            ]);
        }
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}

mysqli_close($conn);
?> 