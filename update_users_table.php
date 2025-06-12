<?php
// Run this script once to update the users table with missing columns
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli('localhost', 'root', '', 'neofit');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$columns = [
    'house_details' => 'VARCHAR(255) DEFAULT NULL',
    'barangay' => 'VARCHAR(100) DEFAULT NULL',
    'city' => 'VARCHAR(100) DEFAULT NULL',
    'province' => 'VARCHAR(100) DEFAULT NULL',
    'region' => 'VARCHAR(100) DEFAULT NULL'
];

foreach ($columns as $col => $type) {
    $exists = $mysqli->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($exists->num_rows === 0) {
        $sql = "ALTER TABLE users ADD COLUMN $col $type";
        if ($mysqli->query($sql)) {
            echo "Added column: $col<br>";
        } else {
            echo "Error adding $col: " . $mysqli->error . "<br>";
        }
    } else {
        echo "Column $col already exists.<br>";
    }
}

$mysqli->close();
echo "Done.";
?> 