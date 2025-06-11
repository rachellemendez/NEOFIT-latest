<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__FILE__) . '/../db.php';
require_once 'payment_functions.php';

// Test data
$test_transaction_id = 'TEST' . time();

// Helper function to print test results
function printResult($test_name, $result) {
    echo "\n=== $test_name ===\n";
    echo "Result: ";
    print_r($result);
    echo "\n";
}

// Test getTotalRevenue
$total_revenue = getTotalRevenue();
printResult("Total Revenue", $total_revenue);

// Test getTodayEarnings
$today_earnings = getTodayEarnings();
printResult("Today's Earnings", $today_earnings);

// Test getPendingPayments
$pending_payments = getPendingPayments();
printResult("Pending Payments", $pending_payments);

// Test getFilteredPayments
$filtered_payments = getFilteredPayments('', 'pending', '', 1, 10);
printResult("Filtered Payments (pending)", $filtered_payments);

// Test getFilteredPaymentsCount
$filtered_count = getFilteredPaymentsCount('', 'pending', '');
printResult("Filtered Payments Count (pending)", $filtered_count);

// Test updatePaymentStatus
$update_result = updatePaymentStatus($test_transaction_id, 'success');
printResult("Update Payment Status", $update_result);

echo "\nDone testing payment functions.\n";
?>
