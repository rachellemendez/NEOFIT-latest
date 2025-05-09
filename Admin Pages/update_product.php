<?php
include '../db.php';

$id = $_POST['id'];
$product_name = $_POST['product_name'];
$quantity_small = (int)$_POST['quantity_small'];
$quantity_medium = (int)$_POST['quantity_medium'];
$quantity_large = (int)$_POST['quantity_large'];
$product_price = (float)$_POST['product_price'];

$sql = "UPDATE products SET product_name=?, quantity_small=?, quantity_medium=?, quantity_large=?, product_price=? WHERE product_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siiidi", $product_name, $quantity_small, $quantity_medium, $quantity_large, $product_price, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
