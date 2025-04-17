<?php

// Connect Php to Database
try{  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli('localhost', 'root', '', 'neofit');
    if($conn->connect_error){
        // echo "Failed To Connect";
    } else {
        // echo "Connected Successfully";
    }
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}

?>