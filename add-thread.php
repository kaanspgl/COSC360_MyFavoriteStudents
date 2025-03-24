<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$title = htmlspecialchars(trim($_POST['title']));
$content = htmlspecialchars(trim($_POST['content']));
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO discussion_threads (title, content, user_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $title, $content, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();
?>
