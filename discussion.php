<?php
session_start();
include 'config.php';
$currentUserId = $_SESSION['user_id'] ?? null;

// Load skill categories for dropdowns
$skills = [];
$skillQuery = $conn->query("SELECT id, skill_name FROM skills ORDER BY skill_name ASC");
while ($row = $skillQuery->fetch_assoc()) {
    $skills[] = $row;
}
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

        document.getElementById("category-filter").addEventListener("change", fetchThreads);
    });

    function fetchThreads() {
        fetch('fetch-threads.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById("discussion-container");
                const selectedCategory = document.getElementById("category-filter").value;

                const grouped = {};
                data.forEach(thread => {
                    if (!grouped[thread.category]) grouped[thread.category] = [];
                    grouped[thread.category].push(thread);
                });

                container.innerHTML = "";
                for (const category in grouped) {
                    if (selectedCategory !== "all" && selectedCategory !== category) continue;

                    container.innerHTML += `<h3 style="margin-top:30px;">${category}</h3>`;
                    grouped[category].forEach(thread => {
                        container.innerHTML += `
                            <div class="discussion-card">
                                <h4><a href="thread.php?id=${thread.id}">${thread.title}</a></h4>
                                <p>${thread.content}</p>
                                <span class="discussion-meta">Posted by ${thread.username} • ${thread.created_at}</span>
                            </div>`;
                    });
                }

                if (container.innerHTML === "") {
                    container.innerHTML = "<p style='text-align:center;'>No threads in this category.</p>";
                }
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

    <div style="text-align: center; margin-bottom: 20px;">
        <label for="category-filter"><strong>Filter by Skill:</strong></label>
        <select id="category-filter">
            <option value="all">All Skills</option>
            <?php foreach ($skills as $skill): ?>
                <option value="<?php echo htmlspecialchars($skill['skill_name']); ?>">
                    <?php echo htmlspecialchars($skill['skill_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($currentUserId): ?>
    <section class="profile-form-container">
        <h3>Create a New Thread</h3>
        <form id="thread-form">
            <label for="title">Thread Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>

            <label for="category">Skill Category:</label>
            <select id="category" name="category" required>
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo htmlspecialchars($skill['skill_name']); ?>">
                        <?php echo htmlspecialchars($skill['skill_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

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
