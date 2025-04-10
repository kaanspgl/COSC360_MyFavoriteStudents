<?php
include 'config.php';

$sql = "SELECT u.username, COUNT(l.id) AS listing_count
        FROM users u
        LEFT JOIN listings l ON u.id = l.user_id
        GROUP BY u.username
        ORDER BY listing_count DESC";

$result = $conn->query($sql);

$usernames = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $usernames[] = $row['username'];
    $counts[] = (int)$row['listing_count'];
}

header('Content-Type: application/json');
echo json_encode([
    'usernames' => $usernames,
    'counts' => $counts
]);
?>
