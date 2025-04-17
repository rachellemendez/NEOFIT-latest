<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the POST request
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    // Reassign the product IDs in sequential order after deletion
    $sql = "SET @rownum := 0";
    $conn->query($sql); // Reset the row number variable
    $sql = "UPDATE products SET id = (@rownum := @rownum + 1)";
    if ($conn->query($sql) === TRUE) {
        echo "IDs updated successfully.";
    } else {
        echo "Error updating IDs: " . $conn->error;
    }

    // Close the connection
    $stmt->close();
}


// Close the connection
$conn->close();

// Redirect back to the product list
header('Location: Admin1.php'); // Adjust to your admin page
exit();
?>
