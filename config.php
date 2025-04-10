<?php
// Remote school database (commented out for now)
// $host = 'localhost';
// $db = 'ka199967';
// $user = 'ka199967';
// $pass = 'Kaan906169!';  

// Local XAMPP MySQL database
$host = 'localhost';
$db = 'ican_youcan';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
