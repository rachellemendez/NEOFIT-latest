<?php
require_once 'db_connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit();
}

try {
    $conn = get_db_connection();
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Don't reveal if email exists or not for security
        echo json_encode(['status' => 'success', 'message' => 'If your email is registered, you will receive password reset instructions shortly.']);
        exit();
    }
    
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store reset token in database
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $expiry);
    $stmt->execute();
    
    // Send reset email
    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;
    $to = $email;
    $subject = "NEOFIT - Password Reset Request";
    $message = "Hello,\n\n";
    $message .= "You have requested to reset your password for your NEOFIT account.\n\n";
    $message .= "Please click the following link to reset your password:\n";
    $message .= $reset_link . "\n\n";
    $message .= "This link will expire in 1 hour.\n\n";
    $message .= "If you did not request this password reset, please ignore this email.\n\n";
    $message .= "Best regards,\nNEOFIT Team";
    
    $headers = "From: noreply@neofit.com";
    
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'If your email is registered, you will receive password reset instructions shortly.']);
    } else {
        throw new Exception("Failed to send email");
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 