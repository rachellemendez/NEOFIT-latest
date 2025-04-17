<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching all products
$sql_all = "SELECT * FROM products";
$result_all = $conn->query($sql_all);

// Fetching live products
$sql_live = "SELECT * FROM products WHERE status='live'";
$result_live = $conn->query($sql_live);

// Fetching unpublished products
$sql_unpublished = "SELECT * FROM products WHERE status='unpublished'";
$result_unpublished = $conn->query($sql_unpublished);

// Handling new product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product-name'])) {
    $product_id = isset($_POST['product-id']) ? $_POST['product-id'] : null;
    $product_name = $_POST['product-name'];
    $product_design = $_POST['product-design'];
    $product_color = $_POST['product-color'];
    $product_size = $_POST['product-size'];
    $product_quantity = (int)$_POST['product-quantity'];
    $product_price = (float)$_POST['product-price'];
    $status = $_POST['status'];

    if (!empty($product_id)) {
        // Update existing product by ID
        $sql_update = "UPDATE products SET 
                        product_name='$product_name', 
                        product_design='$product_design', 
                        product_color='$product_color', 
                        product_size='$product_size', 
                        product_quantity=$product_quantity, 
                        product_price=$product_price, 
                        status='$status' 
                        WHERE id=$product_id";
                        
        if ($conn->query($sql_update) === TRUE) {
            header("Location: Admin1.php");
            exit();
        } else {
            echo "Error updating product: " . $conn->error;
        }
    } else {
        // Check if the same product already exists
        $check_sql = "SELECT * FROM products WHERE 
                        product_name='$product_name' AND 
                        product_design='$product_design' AND 
                        product_color='$product_color' AND 
                        product_size='$product_size'";
        $result_check = $conn->query($check_sql);

        if ($result_check->num_rows > 0) {
            // Product already exists — update quantity and other fields
            $existing = $result_check->fetch_assoc();
            $new_quantity = $existing['product_quantity'] + $product_quantity;
            $existing_id = $existing['id'];

            $update_sql = "UPDATE products SET 
                            product_quantity=$new_quantity, 
                            product_price=$product_price, 
                            status='$status' 
                            WHERE id=$existing_id";
            if ($conn->query($update_sql) === TRUE) {
                header("Location: Admin1.php");
                exit();
            } else {
                echo "Error updating existing product: " . $conn->error;
            }
        } else {
            // Insert new product
            $insert_sql = "INSERT INTO products 
                            (product_name, product_design, product_color, product_size, product_quantity, product_price, status)
                           VALUES 
                            ('$product_name', '$product_design', '$product_color', '$product_size', $product_quantity, $product_price, '$status')";
            if ($conn->query($insert_sql) === TRUE) {
                header("Location: Admin1.php");
                exit();
            } else {
                echo "Error adding new product: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>
