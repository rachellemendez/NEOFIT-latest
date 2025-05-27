<?php
// update_product.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neofit";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['id'])) {
    echo "Missing product ID.";
    $conn->close();
    exit;
}

$id = intval($_POST['id']);
$fieldsToUpdate = [];

// List of all editable fields
$validFields = [
    'product_name',
    'product_status',
    'quantity_small',
    'quantity_medium',
    'quantity_large',
    'product_price',
    'product_category'
];

foreach ($validFields as $field) {
    if (isset($_POST[$field]) && $_POST[$field] !== '') {
        $value = $_POST[$field];

        // Cast numeric values appropriately
        if (in_array($field, ['quantity_small', 'quantity_medium', 'quantity_large'])) {
            $value = intval($value);
        } elseif ($field === 'product_price') {
            $value = floatval($value);
        } else {
            $value = "'" . $conn->real_escape_string($value) . "'";
        }

        $fieldsToUpdate[] = "$field = $value";
    }
}

// Handle image uploads
$uploadDir = 'uploads/'; // Make sure this directory exists and is writable

$imageFields = ['photoFront', 'photo1', 'photo2', 'photo3', 'photo4'];

foreach ($imageFields as $imageField) {
    if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$imageField]['tmp_name'];
        $originalName = basename($_FILES[$imageField]['name']);

        // Clean the filename
        $safeName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
        $targetPath = $uploadDir . $safeName;

        // Move file to upload directory
        if (move_uploaded_file($tmpName, $targetPath)) {
            $fieldsToUpdate[] = "$imageField = '" . $conn->real_escape_string($targetPath) . "'";
        }
    }
}

if (empty($fieldsToUpdate)) {
    echo "No fields to update.";
    $conn->close();
    exit;
}

$sql = "UPDATE products SET " . implode(", ", $fieldsToUpdate) . " WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Product updated successfully!');
        window.location.href = 'all_product_page.php';
    </script>";
} else {
    echo "Error updating product: " . $conn->error;
}

$conn->close();
?>
