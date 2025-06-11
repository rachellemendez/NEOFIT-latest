<?php
if (!defined('DB_CONNECTION_INCLUDED')) {
    define('DB_CONNECTION_INCLUDED', true);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    function get_db_connection() {
        if (!isset($GLOBALS['conn']) || $GLOBALS['conn'] === null) {
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'neofit';
            
            $GLOBALS['conn'] = new mysqli($host, $username, $password, $database);
            
            if ($GLOBALS['conn']->connect_error) {
                throw new Exception("Connection failed: " . $GLOBALS['conn']->connect_error);
            }
        }
        
        return $GLOBALS['conn'];
    }

    try {
        $conn = get_db_connection();
        
        // Create users table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            security_question VARCHAR(50) NOT NULL,
            security_answer VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            address VARCHAR(255) DEFAULT NULL,
            contact VARCHAR(11) DEFAULT NULL,
            neocreds DECIMAL(10,2) DEFAULT 0.00,
            PRIMARY KEY (id),
            UNIQUE KEY unique_email (email)
        )";
        $conn->query($sql);

        // Create password_resets table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            expiry DATETIME NOT NULL,
            used TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_token (token),
            INDEX idx_email (email)
        )";
        $conn->query($sql);

        // Create payments table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_id VARCHAR(100) NOT NULL UNIQUE,
            order_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
            payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_transaction_id (transaction_id),
            INDEX idx_order_id (order_id),
            INDEX idx_status (status),
            INDEX idx_payment_date (payment_date)
        )";
        $conn->query($sql);

        // Create payment_status_logs table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS payment_status_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_id VARCHAR(100) NOT NULL,
            old_status ENUM('pending', 'success', 'failed'),
            new_status ENUM('pending', 'success', 'failed'),
            changed_by VARCHAR(100) NOT NULL,
            changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_transaction_id (transaction_id),
            INDEX idx_changed_at (changed_at),
            FOREIGN KEY (transaction_id) REFERENCES payments(transaction_id)
        )";
        $conn->query($sql);

        // Create neocreds_transactions table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS neocreds_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            user_name VARCHAR(100) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
            request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            process_date TIMESTAMP NULL,
            processed_by VARCHAR(50) NULL,
            admin_notes TEXT NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status),
            INDEX idx_request_date (request_date),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        $conn->query($sql);

    } catch (Exception $e) {
        error_log("Database setup error: " . $e->getMessage());
        throw $e;
    }
}

// Make the connection available globally
global $conn;
if (!isset($conn)) {
    $conn = get_db_connection();
}
