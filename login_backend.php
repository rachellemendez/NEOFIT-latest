<?php
// Start session to track user login status
session_start();

//Database Connection
include 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    // Get email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // **Admin login exception**  
    if ($email === "admin@1" && $password === "admin") {
        $_SESSION['admin@1'] = true;
        header("Location: Admin Pages/all_product_page.php"); // Redirect to the admin panel
        exit();
    }

    // Check if email contains '@' (except for admin)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email !== "admin") {
        echo "Invalid email format.";
        exit();
    }

    // Check if both fields are filled
    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit();
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


            // Redirect to the user dashboard
            header("Location: landing_page.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
