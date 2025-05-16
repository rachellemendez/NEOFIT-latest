<?php
// Database connection
include '../db.php';

function uploadPhoto($inputName) {
    echo "<pre>Debug info for $inputName:\n";
    print_r($_FILES[$inputName]);
    echo "</pre>";

    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$inputName]['tmp_name'];
        $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
        $uploadDir = "../uploads/";
        $targetFile = $uploadDir . $fileName;

        // Check if file is an image
        $fileType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "Error: Only JPG, PNG, and GIF files are allowed.<br>";
            return false;
        }

        // Check file size (max 5MB)
        if ($_FILES[$inputName]['size'] > 5000000) {
            echo "Error: File is too large.<br>";
            return false;
        }

        // Extra checks
        if (!is_dir($uploadDir)) {
            echo "Error: Upload directory does not exist.<br>";
            return false;
        }

        if (!is_writable($uploadDir)) {
            echo "Error: Upload directory is not writable.<br>";
            return false;
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($tmpName, $targetFile)) {
            echo "Success: File '$fileName' uploaded to $targetFile<br>";
            return $targetFile;
        } else {
            echo "Error: move_uploaded_file() failed for $inputName → $targetFile<br>";
            return false;
        }
    } else {
        $error = $_FILES[$inputName]['error'] ?? 'Not set';
        echo "Error: File '$inputName' not uploaded. PHP Upload Error Code: $error<br>";
        return false;
    }
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
        empty($product_name) ||
        !is_numeric($quantity_small) ||
        !is_numeric($quantity_medium) ||
        !is_numeric($quantity_large) ||
        !is_numeric($product_price) ||
        empty($product_status) ||
        empty($product_category) ||
        !$photoFront || !$photo1 || !$photo2 || !$photo3 || !$photo4
    ) {
        echo "Please fill in all fields!";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, quantity_small, quantity_medium, quantity_large, product_price, product_status, photoFront, photo1, photo2, photo3, photo4, product_category) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "siiidsssssss",
        $product_name, $quantity_small, $quantity_medium, $quantity_large, $product_price, $product_status, $photoFront, $photo1, $photo2, $photo3, $photo4, $product_category
    );

    if ($stmt->execute()) {
        header("Location: add_new_product_page.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
