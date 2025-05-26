<?php
// Database connection
include '../db.php';

function uploadPhoto($inputName) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$inputName]['tmp_name'];
        $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
        $uploadDir = "uploads/";
        $targetFile = $uploadDir . $fileName;

        // Check if file is an image (add other types if needed)
        $fileType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "Error: Only JPG, PNG, and GIF files are allowed.";
            return false;
        }

        // Check file size (max 5MB)
        if ($_FILES[$inputName]['size'] > 5000000) { // 5MB max size
            echo "Error: File is too large.";
            return false;
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($tmpName, $targetFile)) {
            return $targetFile; // Return the file path
        } else {
            echo "Error: File upload failed.";
            return false;
        }
    }
    echo "Error: No file uploaded.";
    return false; // If file upload didn't happen
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_submit'])) {
    // Get form data
    $product_name = $_POST['product_name'] ?? '';
    $quantity_small = $_POST['quantity_small'] ?? 0;
    $quantity_medium = $_POST['quantity_medium'] ?? 0;
    $quantity_large = $_POST['quantity_large'] ?? 0;
    $product_price = $_POST['product_price'] ?? 0;
    $product_status = $_POST['product_status'] ?? '';
    $product_category = $_POST['product_category'] ?? '';

    // Handle file uploads
    $photoFront = uploadPhoto('photo_front');
    $photo1 = uploadPhoto('photo_1');
    $photo2 = uploadPhoto('photo_2');
    $photo3 = uploadPhoto('photo_3');
    $photo4 = uploadPhoto('photo_4');

    // Validate all fields
    if (
        empty($product_name) ||                               // Check if product name is empty
        !is_numeric($quantity_small) ||                        // Check if quantity_small is a number
        !is_numeric($quantity_medium) ||                       // Check if quantity_medium is a number
        !is_numeric($quantity_large) ||                        // Check if quantity_large is a number
        !is_numeric($product_price) ||                         // Check if product_price is a number
        empty($product_status) ||                              // Check if product_status is empty
        !$photoFront ||                                
        empty($box_id)                                         // Check if box_id is empty
    ) {
        
        echo "Please fill in all fields!";
        exit;
    }

    // Combine everything into one insert
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, quantity_small, quantity_medium, quantity_large, product_price, product_status, photoFront, photo1, photo2, photo3, photo4, product_category) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "siiidsssssss",  // Adjusted to correct parameter types
        $product_name, $quantity_small, $quantity_medium, $quantity_large, $product_price, $product_status, $photoFront, $photo1, $photo2, $photo3, $photo4, $product_category
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
