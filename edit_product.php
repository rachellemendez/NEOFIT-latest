<?php
include 'admin_backend.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product-name'])) {
    $product_id = isset($_POST['product-id']) ? $_POST['product-id'] : null;
    $product_name = $_POST['product-name'];
    $product_design = $_POST['product-design'];
    $product_color = $_POST['product-color'];
    $product_size = $_POST['product-size'];
    $product_quantity = $_POST['product-quantity'];
    $product_price = $_POST['product-price'];
    $status = $_POST['product_status'];
    $product_category = $_POST['product_category'];

    if ($product_id) {
        // Update existing product
        $sql_update = "UPDATE products SET 
                        product_name='$product_name', 
                        product_design='$product_design', 
                        product_color='$product_color', 
                        product_size='$product_size', 
                        product_quantity=$product_quantity, 
                        product_price=$product_price, 
                        product_status='$status',
                        product_category='$product_category' 
                        WHERE id=$product_id";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: Admin1.php");
        } else {
            echo "Error updating product: " . $conn->error;
        }
    }
}

$conn->close();
?>
