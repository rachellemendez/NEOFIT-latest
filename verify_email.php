<?php
session_start();
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Get user data from unverified_users
    $stmt = $conn->prepare("SELECT * FROM unverified_users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Insert into users table
        $insert_stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $user['email'], $user['password'], $user['first_name'], $user['last_name']);
        
        if ($insert_stmt->execute()) {
            // Delete from unverified_users
            $delete_stmt = $conn->prepare("DELETE FROM unverified_users WHERE verification_token = ?");
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();
            
            echo "Email verified successfully! You can now <a href='login.php'>login</a>.";
        } else {
            echo "Error creating account. Please try again.";
        }
    } else {
        echo "Invalid verification token or token has expired.";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "No verification token provided.";
}
?> 