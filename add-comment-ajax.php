<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$commentText = htmlspecialchars(trim($_POST['comment_text']));
$threadId = intval($_POST['thread_id']);
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO thread_comments (thread_id, user_id, comment_text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $threadId, $userId, $commentText);
$stmt->execute();
$stmt->close();
$conn->close();

echo 'Comment added';
?>
