<?php
session_start();
include 'config.php';

$currentUserId = $_SESSION['user_id'] ?? null;
$threadId = $_GET['id'] ?? null;
if (!$threadId) {
    die("Thread not found.");
}

// Fetch thread info
$stmt = $conn->prepare("SELECT d.title, d.content, d.created_at, u.username FROM discussion_threads d 
                        JOIN users u ON d.user_id = u.id WHERE d.id = ?");
$stmt->bind_param("i", $threadId);
$stmt->execute();
$stmt->bind_result($title, $content, $created_at, $author);
$stmt->fetch();
$stmt->close();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentUserId) {
    $commentText = htmlspecialchars(trim($_POST['comment_text']));
    $insert = $conn->prepare("INSERT INTO thread_comments (thread_id, user_id, comment_text) VALUES (?, ?, ?)");
    $insert->bind_param("iis", $threadId, $currentUserId, $commentText);
    $insert->execute();
    $insert->close();
    header("Location: thread.php?id=$threadId");
    exit;
}

// Fetch comments
$comments = [];
$cStmt = $conn->prepare("SELECT c.comment_text, c.created_at, u.username FROM thread_comments c 
                         JOIN users u ON c.user_id = u.id 
                         WHERE c.thread_id = ? ORDER BY c.created_at ASC");
$cStmt->bind_param("i", $threadId);
$cStmt->execute();
$cStmt->bind_result($commentText, $commentDate, $commentUser);
while ($cStmt->fetch()) {
    $comments[] = ['text' => $commentText, 'date' => $commentDate, 'user' => $commentUser];
}
$cStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Discussion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="discussion-card">
        <h2><?php echo htmlspecialchars_decode($title); ?></h2>
        <p><?php echo htmlspecialchars_decode($content); ?></p>
        <span class="discussion-meta">Posted by <?php echo htmlspecialchars($author); ?> • <?php echo $created_at; ?></span>
    </section>

    <section class="comments-section">
        <h3>Comments:</h3>
        <?php if (empty($comments)): ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-card">
                    <p><strong><?php echo htmlspecialchars($comment['user']); ?>:</strong> 
                    <?php echo htmlspecialchars($comment['text']); ?></p>
                    <span class="comment-date"><?php echo $comment['date']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <?php if ($currentUserId): ?>
    <section class="profile-form-container">
        <h3>Add a Comment</h3>
        <form method="POST">
            <textarea name="comment_text" required placeholder="Write your comment..."></textarea>
            <button type="submit">Post Comment</button>
        </form>
    </section>
    <?php else: ?>
        <p style="text-align:center;">Login to add a comment.</p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
