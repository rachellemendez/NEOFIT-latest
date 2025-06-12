<?php
require_once 'db.php';

// Fix any existing orders with missing or empty status
$update_sql = "UPDATE orders SET status = 'To Pack' WHERE status IS NULL OR status = ''";
if ($conn->query($update_sql)) {
    echo "Updated existing orders with missing status.<br>";
}

// Modify the table structure to enforce the status field constraints
$alter_sql = "ALTER TABLE orders MODIFY COLUMN status enum('To Pack','Packed','In Transit','Delivered','Cancelled','Returned') NOT NULL DEFAULT 'To Pack'";
if ($conn->query($alter_sql)) {
    echo "Updated orders table structure.<br>";
}

// Verify the changes
$verify_sql = "SELECT id, status FROM orders WHERE status != 'To Pack' AND status != 'Packed' AND status != 'In Transit' AND status != 'Delivered' AND status != 'Cancelled' AND status != 'Returned'";
$result = $conn->query($verify_sql);

if ($result->num_rows > 0) {
    echo "Warning: Found " . $result->num_rows . " orders with invalid status.<br>";
    while ($row = $result->fetch_assoc()) {
        echo "Order #" . $row['id'] . " has invalid status: " . $row['status'] . "<br>";
        // Fix these orders
        $conn->query("UPDATE orders SET status = 'To Pack' WHERE id = " . $row['id']);
    }
} else {
    echo "All orders have valid status values.<br>";
}

$conn->close();
echo "Database update completed.";
?> 