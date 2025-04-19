<?php
// Database connection
include '../db.php';

// Cinomment ko muna yung Photos, will decide pa kung iimplement siya
// function uploadPhoto($inputName, $conn) {
//     if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
//         $tmpName = $_FILES[$inputName]['tmp_name'];
//         $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
//         $uploadDir = "uploads/";
//         $targetFile = $uploadDir . $fileName;

//         // Move the uploaded file to the desired directory
//         if (move_uploaded_file($tmpName, $targetFile)) {
//             return $targetFile; // Return the path of the uploaded file
//         } else {
//             return false; // If file move failed
//         }
//     }
//     return false; // If file upload didn't happen
// }


// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_submit'])) {
    // Get form data
    $product_name = $_POST['product_name'] ?? '';
    $quantity_small = $_POST['quantity_small'] ?? 0;
    $quantity_medium = $_POST['quantity_medium'] ?? 0;
    $quantity_large = $_POST['quantity_large'] ?? 0;
    $product_price = $_POST['product_price'] ?? 0;
    $product_status = $_POST['product_status'] ?? '';
    
      // Validate all fields
    if (
        empty($product_name) ||
        !is_numeric($quantity_small) ||
        !is_numeric($quantity_medium) ||
        !is_numeric($quantity_large) ||
        !is_numeric($product_price) ||
        empty($product_status)
        // !$photoFront || !$photo1 || !$photo2 || !$photo3 || !$photo4
    ) {
        echo "All fields and images are required!";
        exit;
    }

    // Combine everything into one insert
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, quantity_small, quantity_medium, quantity_large, product_price, product_status) 
        VALUES (?, ?, ?, ?, ?, ?)"); //?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "siiids", //sssss",
        $product_name, $quantity_small, $quantity_medium, $quantity_large, $product_price, $product_status
        //$photoFront, $photo1, $photo2, $photo3, $photo4
    );

    // Execute and check
    if ($stmt->execute()) {
        header("Location: add_new_product_page.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
