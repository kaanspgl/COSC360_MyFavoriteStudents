<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$category = $_POST['category'] ?? 'General';
$userId = $_SESSION['user_id'];

if ($title && $content) {
    $stmt = $conn->prepare("INSERT INTO discussion_threads (title, content, user_id, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $content, $userId, $category);
    $stmt->execute();
    $stmt->close();
    echo "Success";
} else {
    echo "Missing title or content";
}
?>
