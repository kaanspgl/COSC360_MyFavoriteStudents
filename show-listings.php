<?php
session_start();
include 'config.php';

$currentUserId = $_SESSION['user_id'] ?? null;

$skillFilter = isset($_GET['skill_id']) ? intval($_GET['skill_id']) : null;

$listings = [];
if ($skillFilter) {
    $stmt = $conn->prepare("SELECT listings.id, listings.title, listings.description, listings.image, listings.price, users.username, skills.skill_name, listings.user_id 
                            FROM listings
                            JOIN users ON listings.user_id = users.id
                            JOIN skills ON listings.skill_id = skills.id
                            WHERE skills.id = ?
                            ORDER BY listings.id DESC");
    $stmt->bind_param("i", $skillFilter);
} else {
    $stmt = $conn->prepare("SELECT listings.id, listings.title, listings.description, listings.image, listings.price, users.username, skills.skill_name, listings.user_id 
                            FROM listings
                            JOIN users ON listings.user_id = users.id
                            JOIN skills ON listings.skill_id = skills.id
                            ORDER BY listings.id DESC");
}
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
    <style>
        .toggle-comments-button {
            background-color: #3a885d;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 6px;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <?php if (isset($_GET['deleted'])): ?>
        <p style="text-align: center; color: green; font-weight: bold;">
            Listing has been successfully deleted.
        </p>
    <?php endif; ?>

    <section class="listings-container">
        <?php if (empty($listings)): ?>
            <p style="text-align:center;">No listings found. <a href="create-listing.php">Be the first to create one!</a></p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing-wrapper" style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 40px;">

                    <!-- Listing Content -->
                    <div class="listing-card" style="flex: 2;">
                        <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                        <p><strong>Skill:</strong> <?php echo htmlspecialchars($listing['skill']); ?></p>
                        <p><?php echo htmlspecialchars($listing['description']); ?></p>

                        <?php if (!empty($listing['image'])): ?>
                            <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image" style="max-width: 100%; border-radius: 10px;">
                        <?php endif; ?>

                        <span class="listing-author"><em>Posted by: <?php echo htmlspecialchars($listing['username']); ?></em></span><br>

                        <?php if ($currentUserId === $listing['owner_id']): ?>
                            <a href="edit-listing.php?id=<?php echo $listing['id']; ?>" class="edit-button">Edit Listing</a>
                        <?php else: ?>
                            <button onclick="purchaseListing('<?php echo htmlspecialchars($listing['title']); ?>')">Request Service</button>
                        <?php endif; ?>
                    </div>

                    <!-- Comment Section Panel -->
                    <div class="listing-comments" style="flex: 1; min-width: 250px;">
                        <button class="toggle-comments-button" onclick="toggleComments('comments-<?php echo $listing['id']; ?>')">
                            Show Comments
                        </button>

                        <div id="comments-<?php echo $listing['id']; ?>" class="comments-section" style="display: none; margin-top: 10px;">
                            <h4>Comments:</h4>
                            <?php
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
                                    <div class="comment-card" style="background:#f4f4f4; padding:8px; border-radius:6px; margin-bottom:6px;">
                                        <p><strong><?php echo htmlspecialchars($comment_author); ?>:</strong> 
                                        <?php echo htmlspecialchars($comment_text); ?></p>
                                        <span class="comment-date" style="font-size: 0.85em;"><?php echo $comment_date; ?></span>
                                    </div>
                                <?php endwhile;
                                $commentStmt->close();
                            } else {
                                echo "<p>Error loading comments.</p>";
                            }
                            ?>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form action="add-comment.php" method="POST" class="comment-form" style="margin-top:10px;">
                                    <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                    <textarea name="comment_text" placeholder="Add a comment..." required></textarea>
                                    <button type="submit">Post Comment</button>
                                </form>
                            <?php else: ?>
                                <p><em>Login to add a comment</em></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <script>
    function purchaseListing(listingTitle) {
        alert("Your request to learn '" + listingTitle + "' has been sent to the owner. They will be notified.");
    }

    function toggleComments(id) {
        const el = document.getElementById(id);
        const btn = el.previousElementSibling;
        if (el.style.display === "none") {
            el.style.display = "block";
            btn.textContent = "Hide Comments";
        } else {
            el.style.display = "none";
            btn.textContent = "Show Comments";
        }
    }
    </script>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>

<?php $conn->close(); ?>
</body>
</html>