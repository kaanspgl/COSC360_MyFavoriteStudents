<?php
include 'config.php';

$sql = "SELECT t.id, t.title, t.content, t.created_at, u.username, t.category
        FROM discussion_threads t
        JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC";

$result = $conn->query($sql);
$threads = [];

while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
}

header('Content-Type: application/json');
echo json_encode($threads);
?>
