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
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`)
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

// Drop the unverified_users table if it exists
$sql = "DROP TABLE IF EXISTS `unverified_users`";
if (!$conn->query($sql)) {
    die("Error dropping unverified_users table: " . $conn->error);
}

?>