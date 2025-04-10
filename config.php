<?php
$host = 'localhost';
$db = 'ka199967';
$user = 'ka199967';
$pass = 'Kaan906169!';  

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
