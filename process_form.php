<?php
// Database connection
$servername = "localhost";
$username = "root"; // default username in XAMPP
$password = ""; // default password in XAMPP
$dbname = "neofit"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $product_name = $_POST['product-name'] ?? '';
    $product_design = $_POST['product-design'] ?? '';
    $product_color = $_POST['product-color'] ?? '';
    $product_size = $_POST['product-size'] ?? '';
    $product_quantity = $_POST['product-quantity'] ?? 0;
    $product_price = $_POST['product-price'] ?? 0;
    $status = $_POST['status'] ?? '';

    // Validate if fields are not empty
    if (empty($product_name) || empty($product_design) || empty($product_color) || empty($product_size) || empty($status)) {
        echo "All fields are required!";
        exit;
    }

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_design, product_color, product_size, product_quantity, product_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssids", $product_name, $product_design, $product_color, $product_size, $product_quantity, $product_price, $status);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
