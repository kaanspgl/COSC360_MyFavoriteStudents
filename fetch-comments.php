<?php
session_start();
include 'config.php';

$threadId = $_GET['thread_id'] ?? 0;
$threadId = intval($threadId);  // Ensure it's an integer

if ($threadId <= 0) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$comments = [];

$stmt = $conn->prepare("SELECT c.comment_text, c.created_at, u.username 
                        FROM thread_comments c 
                        JOIN users u ON c.user_id = u.id 
                        WHERE c.thread_id = ? 
                        ORDER BY c.created_at ASC");
$stmt->bind_param("i", $threadId);
$stmt->execute();
$stmt->bind_result($commentText, $commentDate, $commentUser);

while ($stmt->fetch()) {
    $comments[] = [
        'text' => htmlspecialchars($commentText),
        'date' => $commentDate,
        'user' => htmlspecialchars($commentUser)
    ];
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($comments);
?>
