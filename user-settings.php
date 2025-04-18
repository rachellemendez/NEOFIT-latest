<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$user_email = $_SESSION['email'];
$user_name = '';  // Fetch this from your DB or session as required
$address = '';
$contact = '';

// Always load values for DISPLAY
include 'user_settings_backend.php'; // This should set: $user_name, $address, $contact

// If the user has just saved the profile, clear the form fields for new input
if (isset($_GET['saved'])) {
    $address_input = '';
    $contact_input = '';
} else {
    $address_input = $address;
    $contact_input = $contact;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
</head>
<body>
    <button type="button" onclick="window.location.href='landing_page.php'">NeoFit</button>

    <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    <p>Email: <?php echo htmlspecialchars($user_email); ?></p>

    <!-- Form to update address and contact -->
    <form action="save_profile.php" method="POST" autocomplete="off">
        <label for="address">Address</label>
        <input type="text" name="address" placeholder="Put address" value="<?php echo htmlspecialchars($address_input); ?>" autocomplete="off">

        <label for="contact">Contact</label>
        <input type="tel" name="contact" placeholder="Put contact number" value="<?php echo htmlspecialchars($contact_input); ?>" autocomplete="off" pattern="[0-9]{10,11}" title="Please enter a valid contact number (For phone numbers start from '09' only)">

        <button type="submit">Save</button>
    </form>


    <!-- Display the updated address and contact -->
    <h2>Current Address: <?php echo htmlspecialchars($address); ?></h2>
    <h2>Current Contact: <?php echo htmlspecialchars($contact); ?></h2>

    <!-- Change password form -->
    <h2>Change Password</h2>
    <form action="change_password.php" method="POST">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" required>

        <label for="new_password">New Password</label>
        <input type="password" name="new_password" required>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Change Password</button>
    </form>

    <!-- Logout Button -->
    <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('saved')) {
        document.querySelector('input[name="address"]').value = '';
        document.querySelector('input[name="contact"]').value = '';
    }
});

document.querySelector('input[name="contact"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>

</html>
