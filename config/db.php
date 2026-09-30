<?php

// 1. Database Configuration Details
$host = 'localhost';
$db_name = 'hospital_db';
$username = 'root';
$password = ''; // XAMPP default password is empty


try {
    // 2. Create PDO Connection 
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);


    
    // 3. Set Error Mode 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    
    // Default Fetch Mode to  ASSOC (Associative Array) 
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


     // echo "Database Connected Successfully!"; 


} catch (PDOException $e) {

    // Database connect error
    die("Database Connection Failed: " . $e->getMessage());
}
?>