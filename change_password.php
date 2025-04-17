<?php
session_start();

// Ensure the user is logged in and the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Redirect to user settings if form was not submitted via POST
    header("Location: user_settings.php");
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Get the password data from POST
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Ensure all fields are filled
if (!$current_password || !$new_password || !$confirm_password) {
    echo "<script>alert('Please fill in all password fields.'); window.history.back();</script>";
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "neofit");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the current hashed password from the database
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    die("User not found.");
}

$row = $result->fetch_assoc();
$hashed_password = $row['password'];
$stmt->close();

// Verify the current password matches the one stored in the database
if (!password_verify($current_password, $hashed_password)) {
    $conn->close();
    echo "<script>alert('Current password is incorrect.'); window.history.back();</script>";
    exit();
}

// Check if the new password and confirm password match
if ($new_password !== $confirm_password) {
    $conn->close();
    echo "<script>alert('New passwords do not match.'); window.history.back();</script>";
    exit();
}

// Hash the new password
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password in the database
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update_stmt->bind_param("si", $new_hashed, $user_id);
$update_stmt->execute();
$update_stmt->close();
$conn->close();

// Inform the user that the password has been updated
echo "<script>alert('Password changed successfully.'); window.location.href = 'user-settings.php';</script>";
?>
