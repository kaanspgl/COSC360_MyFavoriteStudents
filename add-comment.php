<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: show-listings.php');
    exit;
}

$listing_id = intval($_POST['listing_id']);
$user_id = $_SESSION['user_id'];
$comment_text = htmlspecialchars(trim($_POST['comment_text']));

$stmt = $conn->prepare("INSERT INTO listing_comments (listing_id, user_id, comment_text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $listing_id, $user_id, $comment_text);
$stmt->execute();
$stmt->close();

header("Location: show-listings.php");
exit;
?>
