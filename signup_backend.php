<?php
session_start();
header('Content-Type: application/json');

include 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['signup_submit'])) {
    // Debug: Log the received data
    error_log("Received POST data: " . print_r($_POST, true));

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);

    // Debug: Log the extracted values
    error_log("Extracted values - First Name: $first_name, Last Name: $last_name, Email: $email");

    // Validate all fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($security_question) || empty($security_answer)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Validate names (only letters and spaces allowed)
    if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) {
        echo json_encode(['status' => 'error', 'message' => 'First name can only contain letters and spaces.']);
        exit();
    }

    if (!preg_match("/^[a-zA-Z ]+$/", $last_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Last name can only contain letters and spaces.']);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit();
    }

    // Validate password complexity
    function validatePassword($password) {
        // Check minimum length
        if (strlen($password) < 8) {
            return false;
        }
        
        // Check for at least one letter
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return false;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }

    // Check password complexity
    if (!validatePassword($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long and contain a mix of letters, numbers, and special characters.']);
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email already exists!'
        ]);
        exit();
    }

    // Insert new user with security question and answer
    $sql = "INSERT INTO users (first_name, last_name, email, password, security_question, security_answer) 
            VALUES ('$first_name', '$last_name', '$email', '$password', '$security_question', '$security_answer')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['email'] = $email;
        echo json_encode([
            'status' => 'success',
            'message' => 'Registration successful!',
            'redirect' => 'landing_page.php'
        ]);
    } else {
        // Log the actual MySQL error for debugging
        error_log("MySQL Error: " . mysqli_error($conn));
        echo json_encode([
            'status' => 'error',
            'message' => 'Error occurred during registration. Please try again.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}

mysqli_close($conn);
?>