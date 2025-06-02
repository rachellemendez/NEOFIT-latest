<?php
require_once 'db_connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$token = $_POST['token'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($token) || empty($email) || empty($password) || empty($confirm_password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
    exit();
}

// Validate password strength
if (strlen($password) < 8 ||
    !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password does not meet requirements']);
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
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired reset token']);
        exit();
    }
    
    $row = $result->fetch_assoc();
    if (strtotime($row['expiry']) < time()) {
        echo json_encode(['status' => 'error', 'message' => 'Reset token has expired']);
        exit();
    }
    
    if ($row['email'] !== $email) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid reset token']);
        exit();
    }
    
    // Update password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();
    
    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to update password");
    }
    
    // Mark token as used
    $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    
    echo json_encode(['status' => 'success', 'message' => 'Password has been reset successfully']);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 