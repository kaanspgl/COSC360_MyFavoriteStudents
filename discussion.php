<?php
session_start();
include 'config.php';
$currentUserId = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Threads - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchThreads();

        const threadForm = document.getElementById("thread-form");
        if (threadForm) {
            threadForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(threadForm);
                fetch('add-thread.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(() => {
                    threadForm.reset();
                    fetchThreads();
                });
            });
        }
    });

    function fetchThreads() {
        fetch('fetch-threads.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById("discussion-container");
                container.innerHTML = "";
                data.forEach(thread => {
                    container.innerHTML += `
                        <div class="discussion-card">
                            <h3><a href="thread.php?id=${thread.id}">${thread.title}</a></h3>
                            <p>${thread.content}</p>
                            <span class="discussion-meta">Posted by ${thread.username} • ${thread.created_at}</span>
                        </div>`;
                });
            });
    }
    </script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="placeholder-header">
        <h2>Discussion Threads</h2>
        <p>Click on a thread to view and join the discussion!</p>
    </section>

    <?php if ($currentUserId): ?>
    <section class="profile-form-container">
        <h3>Create a New Thread</h3>
        <form id="thread-form">
            <label for="title">Thread Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>

            <button type="submit">Post Thread</button>
        </form>
    </section>
    <?php else: ?>
        <p style="text-align: center;">Login to start a new discussion.</p>
    <?php endif; ?>

    <section id="discussion-container" class="discussion-container">
        <!-- Threads will load here via AJAX -->
    </section>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
