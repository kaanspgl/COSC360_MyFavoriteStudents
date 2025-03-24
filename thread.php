<?php
session_start();
include 'config.php';

$currentUserId = $_SESSION['user_id'] ?? null;
$threadId = $_GET['id'] ?? null;
if (!$threadId) die("Thread not found.");

// Fetch the thread
$stmt = $conn->prepare("SELECT d.title, d.content, d.created_at, u.username 
                        FROM discussion_threads d 
                        JOIN users u ON d.user_id = u.id WHERE d.id = ?");
$stmt->bind_param("i", $threadId);
$stmt->execute();
$stmt->bind_result($title, $content, $created_at, $author);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title); ?> - Discussion</title>
  <link rel="stylesheet" href="style.css">
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    loadComments();  // Load comments when the page loads

    // Handle comment submission via AJAX
    const commentForm = document.getElementById("comment-form");
    if (commentForm) {
        commentForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(commentForm);
            fetch('add-comment-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                commentForm.reset();   // Clear form after submit
                loadComments();        // Refresh comments
            });
        });
    }
});

// Fetch comments dynamically with error handling
function loadComments() {
    fetch('fetch-comments.php?thread_id=<?php echo $threadId; ?>')
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch comments');
            return response.json();
        })
        .then(data => {
            const commentSection = document.getElementById("comments-section");
            commentSection.innerHTML = "";
            if (data.length === 0) {
                commentSection.innerHTML = "<p>No comments yet. Be the first to comment!</p>";
            } else {
                data.forEach(comment => {
                    commentSection.innerHTML += `
                        <div class="comment-card">
                            <p><strong>${comment.user}:</strong> ${comment.text}</p>
                            <span class="comment-date">${comment.date}</span>
                        </div>`;
                });
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById("comments-section").innerHTML = "<p>Error loading comments.</p>";
        });
}

  </script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <section class="discussion-main-thread">
      <h2><?php echo htmlspecialchars_decode($title); ?></h2>
      <p><?php echo htmlspecialchars_decode($content); ?></p>
      <span class="discussion-meta">Posted by <?php echo htmlspecialchars_decode($author); ?> • <?php echo $created_at; ?></span>
  </section>

  <section>
      <h3>Comments:</h3>
      <div id="comments-section">Loading comments...</div>
  </section>

  <?php if ($currentUserId): ?>
  <section class="profile-form-container">
      <h3>Add a Comment</h3>
      <form id="comment-form">
          <textarea name="comment_text" required placeholder="Write your comment..."></textarea>
          <input type="hidden" name="thread_id" value="<?php echo $threadId; ?>">
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
