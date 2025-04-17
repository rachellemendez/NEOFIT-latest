<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the posted data
$address = $_POST['address'];
$contact = $_POST['contact'];
$user_id = $_SESSION['user_id'];

// Update the database with the new values
$stmt = $conn->prepare("UPDATE users SET address = ?, contact = ? WHERE id = ?");
$stmt->bind_param("ssi", $address, $contact, $user_id);

if ($stmt->execute()) {
    // Update session variables with the new address and contact
    $_SESSION['address'] = $address;
    $_SESSION['contact'] = $contact;

    // Redirect to the user settings page after successful update
    echo "<script>alert('Profile updated successfully!')</script>";

    
    exit();
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
