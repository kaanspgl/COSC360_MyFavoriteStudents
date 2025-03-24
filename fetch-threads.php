<?php
session_start();
include 'config.php';

$result = $conn->query("SELECT d.id, d.title, d.content, d.created_at, u.username 
                        FROM discussion_threads d 
                        JOIN users u ON d.user_id = u.id 
                        ORDER BY d.created_at DESC");

$threads = [];
while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
}
header('Content-Type: application/json');
echo json_encode($threads);
$conn->close();
?>
