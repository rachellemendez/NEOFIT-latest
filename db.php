<?php

// Connect Php to Database
try {  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli('localhost', 'root', '', 'neofit');
    if($conn->connect_error){
        die("Failed To Connect: " . $conn->connect_error);
    }
} catch (mysqli_sql_exception $e) {
    die("Error: " . $e->getMessage());
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `security_question` varchar(50) NOT NULL,
    `security_answer` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `address` varchar(255) DEFAULT NULL,
    `contact` varchar(11) DEFAULT NULL,
    `neocreds` DECIMAL(10,2) DEFAULT 0.00,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`)
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

// Add neocreds column if it doesn't exist
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS neocreds DECIMAL(10,2) DEFAULT 0.00";
if (!$conn->query($sql)) {
    die("Error adding neocreds column: " . $conn->error);
}

// Create neocreds_transactions table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS `neocreds_transactions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `user_name` VARCHAR(100) NOT NULL,
    `user_email` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    `request_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `process_date` TIMESTAMP NULL,
    `processed_by` VARCHAR(50) NULL,
    `admin_notes` TEXT NULL,
    `is_payment` TINYINT(1) DEFAULT 0,
    `order_id` INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (!$conn->query($sql)) {
    die("Error creating neocreds_transactions table: " . $conn->error);
}

// Add is_payment and order_id columns if they don't exist
$sql = "ALTER TABLE neocreds_transactions 
        ADD COLUMN IF NOT EXISTS is_payment TINYINT(1) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS order_id INT NULL";
if (!$conn->query($sql)) {
    die("Error adding neocreds_transactions columns: " . $conn->error);
}

// Drop the unverified_users table if it exists
$sql = "DROP TABLE IF EXISTS `unverified_users`";
if (!$conn->query($sql)) {
    die("Error dropping unverified_users table: " . $conn->error);
}

?>