<?php
// database connection
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$house_details = '';
$barangay = '';
$city = '';
$province = '';
$region = '';
$contact = '';
$house_number = '';
$street_name = '';
$subdivision = '';

if (isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name'];
} else {
    $user_name = 'Guest';
}

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT house_details, barangay, city, province, region, contact FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($house_details, $barangay, $city, $province, $region, $contact);
    $stmt->fetch();
    $stmt->close();

    // Parse house_details into components if it exists
    if (!empty($house_details)) {
        $parts = explode(', ', $house_details);
        if (count($parts) >= 2) {
            $house_number = $parts[0];
            $street_name = $parts[1];
            if (count($parts) > 2) {
                $subdivision = $parts[2];
            }
        }
    }

    // Create complete address for display
    if (!empty($house_details) && !empty($barangay) && !empty($city) && !empty($province) && !empty($region)) {
        $address = $house_details . ', ' . $barangay . ', ' . $city . ', ' . $province . ', ' . $region;
    } else {
        $address = 'No address found';
    }
}

$conn->close();
?>