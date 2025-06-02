<?php
session_start();
include 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Log the received data
    error_log("Received POST data: " . print_r($_POST, true));

    // Check if form was submitted
    if (!isset($_POST['signup_submit'])) {
        error_log("signup_submit not set in POST data");
        echo json_encode(['status' => 'error', 'message' => 'Invalid form submission']);
        exit();
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Debug: Log the extracted values
    error_log("Extracted values - First Name: $first_name, Last Name: $last_name, Email: $email");

    // Validate all fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
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

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user account directly
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare insert statement: " . $conn->error);
        }
        $stmt->bind_param("ssss", $email, $hashed_password, $first_name, $last_name);
        
        if ($stmt->execute()) {
            // Set session variables
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Registration successful! Redirecting to your account...',
                'redirect' => 'landing_page.php'
            ]);
        } else {
            throw new Exception("Failed to create account: " . $stmt->error);
        }
    } catch (Exception $e) {
        error_log("Error in signup process: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again later.']);
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>