<?php
include '../db.php';

function getTotalRevenue() {
    global $conn;
    $query = "SELECT SUM(amount) as total FROM payments WHERE status = 'success'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getTodayEarnings() {
    global $conn;
    $query = "SELECT SUM(amount) as total FROM payments 
              WHERE status = 'success' 
              AND DATE(payment_date) = CURDATE()";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getPendingPayments() {
    global $conn;
    $query = "SELECT SUM(amount) as total FROM payments WHERE status = 'pending'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

// Function to handle payment filtering
function getFilteredPayments($search = '', $status = '', $date = '', $page = 1, $limit = 10) {
    global $conn;
    
    $offset = ($page - 1) * $limit;
    $where_clauses = [];
    $params = [];
    
    if (!empty($search)) {
        $where_clauses[] = "(o.order_id LIKE ? OR c.customer_name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($status)) {
        $where_clauses[] = "p.status = ?";
        $params[] = $status;
    }
    
    if (!empty($date)) {
        $where_clauses[] = "DATE(p.payment_date) = ?";
        $params[] = $date;
    }
    
    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $query = "SELECT p.*, o.order_id, c.customer_name 
              FROM payments p 
              JOIN orders o ON p.order_id = o.order_id 
              JOIN customers c ON o.customer_id = c.customer_id 
              $where_sql 
              ORDER BY p.payment_date DESC 
              LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $payments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
    
    return $payments;
}

// Function to get total number of filtered payments
function getFilteredPaymentsCount($search = '', $status = '', $date = '') {
    global $conn;
    
    $where_clauses = [];
    $params = [];
    
    if (!empty($search)) {
        $where_clauses[] = "(o.order_id LIKE ? OR c.customer_name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($status)) {
        $where_clauses[] = "p.status = ?";
        $params[] = $status;
    }
    
    if (!empty($date)) {
        $where_clauses[] = "DATE(p.payment_date) = ?";
        $params[] = $date;
    }
    
    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $query = "SELECT COUNT(*) as total 
              FROM payments p 
              JOIN orders o ON p.order_id = o.order_id 
              JOIN customers c ON o.customer_id = c.customer_id 
              $where_sql";
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}
?> 