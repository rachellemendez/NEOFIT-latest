<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$house_number = trim($_POST['house_number']);
$street_name = trim($_POST['street_name']);
$subdivision = trim($_POST['subdivision']);
$barangay = trim($_POST['barangay']);
$city = trim($_POST['city']);
$region = trim($_POST['region']);
$contact = trim($_POST['contact']);
$user_id = $_SESSION['user_id'];

// Combine house details
$house_details = $house_number . ' ' . $street_name;
if (!empty($subdivision)) {
    $house_details .= ', ' . $subdivision;
}

// Prepare SQL
$stmt = $conn->prepare("UPDATE users SET house_details = ?, barangay = ?, city = ?, region = ?, contact = ? WHERE id = ?");
$stmt->bind_param("sssssi", $house_details, $barangay, $city, $region, $contact, $user_id);

if ($stmt->execute()) {
    echo "<script>
        alert('Profile saved successfully!');
        window.location.href = 'user-settings.php?saved=true';
    </script>";
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
