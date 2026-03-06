<?php

require_once 'config/db.php';

$database = new Database();
$conn = $database->getConnection();

if($conn){
    echo "Database connected successfully.";
}
?>