<?php
// Database connection
include '../db.php';

function uploadPhoto($inputName, $isRequired = false) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] == UPLOAD_ERR_NO_FILE) {
        if ($isRequired) {
            echo "Error: $inputName is required.<br>";
            return false;
        }
        return null; // Return null for optional photos that weren't uploaded
    }

    if ($_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
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
            return $targetFile;
        } else {
            echo "Error: Failed to move uploaded file $inputName.<br>";
            return false;
        }
    } else {
        if ($isRequired) {
            echo "Error: File upload failed for $inputName. Error code: " . $_FILES[$inputName]['error'] . "<br>";
            return false;
        }
        return null;
    }
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_submit'])) {
    // Get form data
    $product_name = trim($_POST['product_name'] ?? '');
    $quantity_small = $_POST['quantity_small'] ?? 0;
    $quantity_medium = $_POST['quantity_medium'] ?? 0;
    $quantity_large = $_POST['quantity_large'] ?? 0;
    $product_price = $_POST['product_price'] ?? 0;
    $product_status = $_POST['product_status'] ?? '';
    $product_category = $_POST['product_category'] ?? '';

    // SERVER-SIDE NAME VALIDATION

    // Check if name is empty
    if (empty($product_name)) {
        echo "Error: Product name is required.";
        exit;
    }

    // Check if name is only numbers
    if (preg_match('/^[0-9]+$/', $product_name)) {
        echo "Error: Product name cannot be numbers only.";
        exit;
    }

    // Check if name is only special characters
    if (preg_match('/^[^a-zA-Z0-9]+$/', $product_name)) {
        echo "Error: Product name cannot contain only special characters.";
        exit;
    }

    // Check if name already exists (case-sensitive)
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM products WHERE BINARY product_name = ?");
    $stmtCheck->bind_param("s", $product_name);
    $stmtCheck->execute();
    $stmtCheck->bind_result($nameCount);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($nameCount > 0) {
        echo "Error: Product name already exists.";
        exit;
    }

    // Handle file uploads
    $photoFront = uploadPhoto('photo_front', true); // Front photo is required
    $photo1 = uploadPhoto('photo_1'); // Optional photos
    $photo2 = uploadPhoto('photo_2');
    $photo3 = uploadPhoto('photo_3');
    $photo4 = uploadPhoto('photo_4');

    // Validate required fields
    if (
        empty($product_name) ||                               // Check if product name is empty
        !is_numeric($quantity_small) ||                        // Check if quantity_small is a number
        !is_numeric($quantity_medium) ||                       // Check if quantity_medium is a number
        !is_numeric($quantity_large) ||                        // Check if quantity_large is a number
        !is_numeric($product_price) ||                         // Check if product_price is a number
        empty($product_status) ||                              // Check if product_status is empty
        $photoFront === false                                  // Front photo upload failed or is missing
    ) {
        echo "Please fill in all required fields! Front photo is required.";
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
