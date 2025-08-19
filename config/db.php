<?php
// Core PHP POS Medical Inventory - Basic Structure Outline
// config/db.php - database connection

$host = 'localhost';
$db   = 'core_mbocw_cess';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>