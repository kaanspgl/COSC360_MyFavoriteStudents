<?php
session_start();
include 'config.php';

$currentUserId = $_SESSION['user_id'] ?? null;

// Fetch Listings
$listings = [];
$stmt = $conn->prepare("SELECT l.id, l.title, l.description, l.image, l.price, u.username, s.skill_name, l.user_id 
                        FROM listings l
                        JOIN users u ON l.user_id = u.id
                        JOIN skills s ON l.skill_id = s.id
                        ORDER BY l.created_at DESC");
$stmt->execute();
$stmt->bind_result($listing_id, $title, $description, $image, $price, $username, $skill_name, $listing_owner_id);
while ($stmt->fetch()) {
    $listings[] = [
        'id' => $listing_id,
        'title' => $title,
        'description' => $description,
        'image' => $image,
        'price' => $price,
        'username' => $username,
        'skill' => $skill_name,
        'owner_id' => $listing_owner_id
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

        <?php if (empty($listings)): ?>
            <p style="text-align:center;">No listings found. <a href="create-listing.php">Be the first to create one!</a></p>
        <?php else: ?>

            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                    <p><strong>Skill:</strong> <?php echo htmlspecialchars($listing['skill']); ?></p>
                    <p><?php echo htmlspecialchars($listing['description']); ?></p>

                    <?php if ($listing['image']): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image">
                    <?php endif; ?>

                    <span class="listing-author">Posted by: <?php echo htmlspecialchars($listing['username']); ?></span>

                    <?php if ($currentUserId === $listing['owner_id']): ?>
                        <a href="edit-listing.php?id=<?php echo $listing['id']; ?>" class="edit-button">Edit Listing</a>
                    <?php else: ?>
                        <button onclick="purchaseListing('<?php echo htmlspecialchars($listing['title']); ?>')">Request Service</button>
                    <?php endif; ?>
                </div>

                <!-- COMMENTS Section -->
                <div class="comments-section">
                    <h4>Comments:</h4>

                    <?php
                    // Prepare and fetch comments for this listing
                    $commentStmt = $conn->prepare("SELECT c.comment_text, u.username, c.created_at 
                                                   FROM listing_comments c 
                                                   JOIN users u ON c.user_id = u.id 
                                                   WHERE c.listing_id = ? 
                                                   ORDER BY c.created_at DESC");
                    if ($commentStmt) {
                        $commentStmt->bind_param("i", $listing['id']);
                        $commentStmt->execute();
                        $commentStmt->bind_result($comment_text, $comment_author, $comment_date);

                        while ($commentStmt->fetch()): ?>
                            <div class="comment-card">
                                <p><strong><?php echo htmlspecialchars($comment_author); ?>:</strong> 
                                <?php echo htmlspecialchars($comment_text); ?></p>
                                <span class="comment-date"><?php echo $comment_date; ?></span>
                            </div>
                        <?php endwhile;

                        $commentStmt->close();
                    } else {
                        echo "<p>Error loading comments.</p>";
                    }
                    ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="add-comment.php" method="POST" class="comment-form">
                            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                            <textarea name="comment_text" placeholder="Add a comment..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p><em>Login to add a comment</em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <script>
    function purchaseListing(listingTitle) {
        alert("Your request to purchase '" + listingTitle + "' has been sent to the owner. They will be notified.");
    }
    </script>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>

<?php $conn->close();?>
</body>
</html>
