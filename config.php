<?php

$dsn = "mysql:host=localhost;dbname=php_form_db";
$userName = "root";
$password = "";


try {

    $conn = new PDO($dsn, $userName, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    error_log("Connection Failed in " . __FILE__ . "on"  . __LINE__ . $e->getMessage());
    
}
