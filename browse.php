<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Skills - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="browse.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

    

    <main>
        <!-- Placeholder Header -->
        <section class="placeholder-header">
            <h2>This is a placeholder until we set up the PHP</h2>
        </section>

        <section class="search-container">
            <input type="text" id="search" placeholder="Search for skills..." onkeyup="filterSkills()">
        </section>

        <section class="skills-list">
            <div class="skill-card">
                <h3>Web Development</h3>
                <p>Learn how to build websites using HTML, CSS, and JavaScript.</p>
                <div class="card-footer">
                    <button class="learn-more">Learn More</button>
                </div>
            </div>
        
            <div class="skill-card">
                <h3>Photography</h3>
                <p>Master the art of capturing stunning photos with your camera.</p>
                <div class="card-footer">
                    <button class="learn-more">Learn More</button>
                </div>
            </div>
        
            <div class="skill-card">
                <h3>Cooking</h3>
                <p>Explore different cuisines and enhance your culinary skills.</p>
                <div class="card-footer">
                    <button class="learn-more">Learn More</button>
                </div>
            </div>
        </section>
        
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
</body>
</html>
