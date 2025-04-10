<?php
include 'config.php';

$sql = "SELECT s.skill_name, COUNT(l.id) AS listing_count
        FROM skills s
        LEFT JOIN listings l ON s.id = l.skill_id
        GROUP BY s.skill_name
        ORDER BY listing_count DESC";

$result = $conn->query($sql);

$skills = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
    $counts[] = (int)$row['listing_count'];
}

header('Content-Type: application/json');
echo json_encode([
    'skills' => $skills,
    'counts' => $counts
]);
?>
