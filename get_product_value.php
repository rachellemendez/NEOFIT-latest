<?php

$host = 'localhost';
$dbname = 'neofit';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$product_id = 37;  // This would be dynamic based on the URL or input

// First, fetch the main product to get the name    
$sql_main = 'SELECT * FROM products WHERE id = :product_id'; 
$stmt_main = $pdo->prepare($sql_main);
$stmt_main->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt_main->execute();
$product = $stmt_main->fetch(PDO::FETCH_ASSOC);

if ($product) {
    // Get the product name from the main product
    $product_name = $product['product_name'];
    $product_price = $product['product_price']; 
    $product_quantity = $product['product_quantity']; 

    // Now, fetch all variants of the product based on product name
    $sql_variants = 'SELECT * FROM products WHERE product_name = :product_name';
    $stmt_variants = $pdo->prepare($sql_variants);
    $stmt_variants->bindParam(':product_name', $product_name, PDO::PARAM_STR);
    $stmt_variants->execute();
    $variants = $stmt_variants->fetchAll(PDO::FETCH_ASSOC);

    // Extract unique colors from the variants
    $unique_colors = array_unique(array_column($variants, 'product_color'));

    // Now, you can pass the product data and unique colors to the HTML
    $product_data = [
        'product_name' => $product_name,
        'product_price' => $product_price,
        'product_quantity' => $product_quantity,
        'variants' => $variants,
        'unique_colors' => $unique_colors
    ];
} else {
    echo 'Product not found.';
}

?>
