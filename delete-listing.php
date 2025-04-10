<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$listingId = $_GET['id'] ?? null;
$currentUserId = $_SESSION['user_id'];
$isAdmin = $_SESSION['is_admin'] ?? 0;

if (!$listingId) {
    die("Invalid listing ID.");
}

// Check listing ownership
$stmt = $conn->prepare("SELECT user_id FROM listings WHERE id = ?");
$stmt->bind_param("i", $listingId);
$stmt->execute();
$stmt->bind_result($ownerId);
$stmt->fetch();
$stmt->close();

if (!$isAdmin && $currentUserId !== $ownerId) {
    die("Unauthorized: You do not have permission to delete this listing.");
}

$deleteComments = $conn->prepare("DELETE FROM listing_comments WHERE listing_id = ?");
$deleteComments->bind_param("i", $listingId);
$deleteComments->execute();
$deleteComments->close();

$delStmt = $conn->prepare("DELETE FROM listings WHERE id = ?");
$delStmt->bind_param("i", $listingId);
$success = $delStmt->execute();
$delStmt->close();

if ($success && $isAdmin) {
    $logStmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action_type, target_id, target_type) VALUES (?, 'delete_post', ?, 'post')");
    $logStmt->bind_param("ii", $currentUserId, $listingId);
    $logStmt->execute();
    $logStmt->close();
}

header("Location: show-listings.php?deleted=1");
exit;
?>
