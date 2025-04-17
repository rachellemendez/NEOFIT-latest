<?php
session_start();

$address = 'No address found';
$contact = 'No contact found';

// Connect to your database
$conn = new mysqli("localhost", "root", "", "neofit"); // Replace 'your_db_name' accordingly

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name']; // Set user_name from session
} else {
    $user_name = 'Guest'; // Fallback if no user is logged in
}

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT address, contact FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($address, $contact);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>