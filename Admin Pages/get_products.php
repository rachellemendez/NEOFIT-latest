<?php
include '../db.php';

$status = isset($_GET['status']) ? $_GET['status'] : 'All';  // Get the status from the URL, default to 'All'

if ($status == 'Live') {
    $sql = "SELECT * FROM products WHERE product_status = 'Live'";
} elseif ($status == 'Unpublished') {
    $sql = "SELECT * FROM products WHERE product_status = 'Unpublished'";
} else {
    $sql = "SELECT * FROM products";  // Get all products if status is 'All' or not specified
}

$result = $conn->query($sql);

$total_small = $total_medium = $total_large = $total_price = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_stock = $row['quantity_small'] + $row['quantity_medium'] + $row['quantity_large'];
        $product_total_price = $row['product_price'] * $total_stock;

        // Add to running totals
        $total_small += $row['quantity_small'];
        $total_medium += $row['quantity_medium'];
        $total_large += $row['quantity_large'];
        $total_price += $product_total_price;

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
        echo "<td>" . $row['quantity_small'] . "</td>";
        echo "<td>" . $row['quantity_medium'] . "</td>";
        echo "<td>" . $row['quantity_large'] . "</td>";
        echo "<td>" . $total_stock . "</td>";
        echo "<td>" . number_format($row['product_price'], 2) . "</td>";
        echo "<td>" . number_format($product_total_price, 2) . "</td>";

        // Add Edit button inside a form
        echo "<td>
            <form action='edit_product.php' method='get' style='display:inline;'>
                <input type='hidden' name='id' value='{$row['id']}'>
                <button type='submit' class='edit-btn'>Edit</button>
            </form>
        </td>";

        echo "</tr>";
    }

    // Display totals row
    $grand_total_stocks = $total_small + $total_medium + $total_large;

    echo "<tr style='font-weight:bold'>";
    echo "<td>Total</td>";
    echo "<td>$total_small</td>";
    echo "<td>$total_medium</td>";
    echo "<td>$total_large</td>";
    echo "<td>$grand_total_stocks</td>";
    echo "<td>-</td>";
    echo "<td>" . number_format($total_price, 2) . "</td>";
    echo "<td>-</td>"; // empty action column for totals row
    echo "</tr>";
} else {
    echo "<tr><td colspan='8'>No products found.</td></tr>";
}
?>
