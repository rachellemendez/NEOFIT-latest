<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

try {
    // Get form input
    $house_number = trim($_POST['house_number'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $place_type = trim($_POST['place_type'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $user_id = $_SESSION['user_id'];

    // Validate required fields
    if (empty($house_number) || empty($street) || empty($barangay) || 
        empty($city) || empty($province) || empty($region)) {
        throw new Exception('Please fill in all required fields');
    }

    // Validate contact number format
    if (!empty($contact) && !preg_match('/^09\d{9}$/', $contact)) {
        throw new Exception('Contact number must start with 09 and be 11 digits long');
    }

    // Prepare SQL
    $stmt = $conn->prepare("UPDATE users SET house_number = ?, street = ?, place_type = ?, barangay = ?, city = ?, province = ?, region = ?, contact = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $house_number, $street, $place_type, $barangay, $city, $province, $region, $contact, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error updating profile: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Redirect with success message
    header('Location: user-settings.php?saved=1');
    exit();

} catch (Exception $e) {
    // Redirect with error message
    header('Location: user-settings.php?error=' . urlencode($e->getMessage()));
    exit();
}
?>
