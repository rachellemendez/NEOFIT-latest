<?php
session_start();

// Check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['user_name'])) {
    die("You must be logged in to place an order.");
}

// DB connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$product_name = $_POST['product_name'] ?? '';
$product_price = $_POST['product_price'] ?? 0.00;
$size = $_POST['size'] ?? '';
$color = $_POST['color'] ?? '';
$quantity = $_POST['quantity'] ?? 1;
$payment_method = $_POST['payment_method'] ?? 'Unknown';
$delivery_address = $_POST['delivery_address'] ?? 'Unknown';
$contact_number = $_POST['contact_number'] ?? 'Unknown';
$status = 'Pending';

$total_price = $product_price * $quantity;

// Get user data from session
$user_email = $_SESSION['email'];
$user_name = $_SESSION['user_name'];

// Insert order into DB
$sql = "INSERT INTO orders (
            user_name, user_email,
            payment_method, delivery_address, contact_number, status,
            product_name, price, size, color, quantity, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssdd", 
    $user_name, $user_email, 
    $payment_method, $delivery_address, $contact_number, $status,
    $product_name, $product_price, $size, $color, $quantity, $total_price
);

if ($stmt->execute()) {
    echo "<script>
            alert('Order placed successfully!');
            window.location.href = '/carl ne05/product_detail.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
