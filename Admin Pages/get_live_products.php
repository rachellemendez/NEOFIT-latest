<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed");
}

$sql = "SELECT product_name FROM products WHERE product_status = 'live' LIMIT 100";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row['product_name'];
}

echo json_encode($products);
$conn->close();
?>
