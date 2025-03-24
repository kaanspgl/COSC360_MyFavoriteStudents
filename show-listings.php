<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Listings - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="show-listings.js"></script>
</head>
<body>
<?php include 'header.php'; ?>


<<<<<<< Updated upstream
    <main>
        <section class="placeholder-header">
            <h2>This is a placeholder until we set up the database</h2>
        </section>

        <section class="listings-container">
            <div class="listing-card">
                <h3>Web Development</h3>
                <p>Learn how to build websites using HTML, CSS, and JavaScript.</p>
                <span class="listing-author">Posted by: Alex J.</span>
            </div>

            <div class="listing-card">
                <h3>Photography</h3>
                <p>Master the art of capturing stunning photos with your camera.</p>
                <span class="listing-author">Posted by: Sarah L.</span>
            </div>

            <div class="listing-card">
                <h3>Cooking</h3>
                <p>Explore different cuisines and enhance your culinary skills.</p>
                <span class="listing-author">Posted by: Michael T.</span>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
=======
        <?php if (empty($listings)): ?>
            <p style="text-align:center;">No listings found. <a href="create-listing.php">Be the first to create one!</a></p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                    <p><strong>Skill:</strong> <?php echo htmlspecialchars($listing['skill']); ?></p>
                    <p><?php echo htmlspecialchars($listing['description']); ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($listing['price'], 2); ?></p>
                    <?php if ($listing['image']): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image">
                    <?php endif; ?>
                    <span class="listing-author">Posted by: <?php echo htmlspecialchars($listing['username']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>


<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
>>>>>>> Stashed changes
</body>
</html>
