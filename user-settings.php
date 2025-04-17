<?php
include 'user_settings_backend.php';

// Assuming the user's email is stored in the session
$user_email = $_SESSION['email'] ?? 'Email not found';
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
    <form action="save_profile.php" method="POST">
        <label for="address">Address</label>
        <input type="text" name="address" placeholder="Put address" value="<?php echo htmlspecialchars($address); ?>">

        <label for="contact">Contact</label>
        <input type="number" name="contact" placeholder="Put contact number" value="<?php echo htmlspecialchars($contact); ?>">

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
</html>
