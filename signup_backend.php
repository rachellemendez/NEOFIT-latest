<?php
// Database connection
include 'db.php';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'index.php';  // Redirect to login page
                  </script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>