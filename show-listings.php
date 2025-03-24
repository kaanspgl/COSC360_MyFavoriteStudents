<?php
session_start();
include 'config.php';

// Fetch listings with skill names and author
$listings = [];
$stmt = $conn->prepare("SELECT l.title, l.description, l.image, l.price, u.username, s.skill_name 
                        FROM listings l
                        JOIN users u ON l.user_id = u.id
                        JOIN skills s ON l.skill_id = s.id
                        ORDER BY l.created_at DESC");
$stmt->execute();
$stmt->bind_result($title, $description, $image, $price, $username, $skill_name);

while ($stmt->fetch()) {
    $listings[] = [
        'title' => $title,
        'description' => $description,
        'image' => $image,
        'price' => $price,
        'username' => $username,
        'skill' => $skill_name
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Listings - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="listings-container">
        <h2>Available Skill Listings</h2>

        <?php if (empty($listings)): ?>
            <p>No listings found. Be the first to <a href="create-listing.php">create a listing</a>!</p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                    <p><strong>Skill:</strong> <?php echo htmlspecialchars($listing['skill']); ?></p>
                    <p><?php echo htmlspecialchars($listing['description']); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($listing['price'], 2)); ?></p>
                    
                    <?php if ($listing['image']): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image" style="max-width:200px;">
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
</body>
</html>
