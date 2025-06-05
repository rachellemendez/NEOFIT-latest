<?php
// Start session to track user login status
session_start();

//Database Connection
include 'db.php';

// Function to send JSON response
function sendJsonResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from the POST request
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // **Admin login exception**  
    if ($email === "admin@1" && $password === "admin") {
        $_SESSION['admin@1'] = true;
        sendJsonResponse('success', 'Admin login successful');
    }

    // Check if email contains '@' (except for admin)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email !== "admin") {
        sendJsonResponse('error', 'Invalid email format');
    }

    // Check if both fields are filled
    if (empty($email) || empty($password)) {
        sendJsonResponse('error', 'Email and password are required');
    }

    // Prepare and execute the SQL query to find the user by email
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If the user exists
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($id, $db_email, $db_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $db_email;

            // Get User's name to display
            $stmt_name = $conn->prepare("SELECT first_name, last_name FROM users WHERE email = ?");
            $stmt_name->bind_param("s", $db_email);
            $stmt_name->execute();
            $stmt_name->bind_result($first_name, $last_name);
            $stmt_name->fetch();

            //Store the name in session
            $user_name = $first_name . ' ' . $last_name;
            $_SESSION['user_name'] = $user_name;

            // Send success response
            sendJsonResponse('success', 'Login successful');
        } else {
            sendJsonResponse('error', 'Invalid email or password');
        }
    } else {
        sendJsonResponse('error', 'Invalid email or password');
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
