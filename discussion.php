<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Threads - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="placeholder-header">
            <h2>This is a placeholder for the discussion thread page</h2>
            <p>Later, this page will display live discussion threads from the database with AJAX support.</p>
        </section>

        <section class="discussion-container">
            <div class="discussion-card">
                <h3>How to start learning photography?</h3>
                <p>I've always been curious about photography. What’s the best way to get started?</p>
                <span class="discussion-meta">Posted by Sarah • Jan 27, 2025</span>
            </div>

            <div class="discussion-card">
                <h3>Best resources for beginner web dev?</h3>
                <p>Can anyone recommend some good online resources for HTML/CSS/JS?</p>
                <span class="discussion-meta">Posted by Alex • Jan 26, 2025</span>
            </div>

            <div class="discussion-card">
                <h3>What camera gear do you use?</h3>
                <p>Just curious what camera models and lenses people here prefer!</p>
                <span class="discussion-meta">Posted by Mike • Jan 24, 2025</span>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
</body>
</html>
