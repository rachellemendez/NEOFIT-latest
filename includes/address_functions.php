<?php
/**
 * Get complete address from individual components
 * @param array $address_data Array containing address components
 * @return string Complete address as comma-separated string
 */
function get_complete_address($address_data) {
    $address_parts = array_filter([
        $address_data['house_number'] ?? '',
        $address_data['street'] ?? '',
        $address_data['place_type'] ?? '',
        $address_data['barangay'] ?? '',
        $address_data['city'] ?? '',
        $address_data['province'] ?? '',
        $address_data['region'] ?? ''
    ]);
    return implode(', ', $address_parts);
}

/**
 * Get user's address from database
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 * @return array|false Array of address components or false if not found
 */
function get_user_address($user_id, $conn) {
    $stmt = $conn->prepare("SELECT house_number, street, place_type, barangay, city, province, region FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $address_data = $result->fetch_assoc();
    $stmt->close();
    return $address_data;
}
?> 