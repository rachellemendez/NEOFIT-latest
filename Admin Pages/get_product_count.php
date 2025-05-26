<?php
include '../db.php';

$status = isset($_GET['status']) ? $_GET['status'] : 'All';

if ($status === 'Live') {
    $sql = "SELECT COUNT(*) as total FROM products WHERE product_status = 'Live'";
} elseif ($status === 'Unpublished') {
    $sql = "SELECT COUNT(*) as total FROM products WHERE product_status = 'Unpublished'";
} else {
    $sql = "SELECT COUNT(*) as total FROM products";
}

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo $row['total'] . " product" . ($row['total'] != 1 ? "s" : "");
} else {
    echo "0 products";
}
?>
