<?php
include '../db.php'; // Include the database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure the ID is an integer

    // SQL to delete the product based on its ID
    $sql = "DELETE FROM products WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect to Admin1.php with success query parameter
            header("Location: all_product_page.php?delete=success");
        } else {
            // If no rows were affected, something went wrong
            header("Location: all_product_page.php?delete=error");
        }
        $stmt->close();
    } else {
        // SQL error
        header("Location: all_product_page.php?delete=error");
    }
}
?>
