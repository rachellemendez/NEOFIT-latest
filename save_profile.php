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
$address = trim($_POST['address']);
$contact = trim($_POST['contact']);
$user_id = $_SESSION['user_id'];

// Build the SQL dynamically based on filled fields
$fieldsToUpdate = [];
$params = [];
$types = "";

if (!empty($address)) {
    $fieldsToUpdate[] = "address = ?";
    $params[] = $address;
    $types .= "s";
}

if (!empty($contact)) {
    $fieldsToUpdate[] = "contact = ?";
    $params[] = $contact;
    $types .= "s";
}

// If nothing is filled, don't update
if (empty($fieldsToUpdate)) {
    echo "<script>
        alert('No changes made.');
        window.location.href = 'user-settings.php';
    </script>";
    exit();
}

// Build final query
$sql = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Bind parameters dynamically
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Update session variables if updated
    if (!empty($address)) $_SESSION['address'] = $address;
    if (!empty($contact)) $_SESSION['contact'] = $contact;

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
