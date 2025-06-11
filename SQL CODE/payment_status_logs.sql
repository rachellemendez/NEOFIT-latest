CREATE TABLE IF NOT EXISTS payment_status_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaction_id VARCHAR(50) NOT NULL,
    old_status VARCHAR(20) NOT NULL,
    new_status VARCHAR(20) NOT NULL,
    changed_by VARCHAR(50) NOT NULL,
    changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES payments(transaction_id)
);
