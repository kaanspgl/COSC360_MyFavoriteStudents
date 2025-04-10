<?php
session_start();
include 'config.php';

$viewedUserId = $_GET['id'] ?? null;

if (!$viewedUserId || !is_numeric($viewedUserId)) {
    die("Invalid profile.");
}

// Get user info
$stmt = $conn->prepare("SELECT username, email, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $viewedUserId);
$stmt->execute();
$stmt->bind_result($username, $email, $bio, $profile_picture);
$stmt->fetch();
$stmt->close();

// Get user's listings
$listings = [];
$stmt = $conn->prepare("SELECT id, title, description, image FROM listings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $viewedUserId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $listings[] = $row;
}
$stmt->close();

// Get user's threads
$threads = [];
$stmt = $conn->prepare("SELECT id, title, created_at FROM discussion_threads WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $viewedUserId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
}
$stmt->close();

$isOwner = ($_SESSION['user_id'] ?? null) == $viewedUserId;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($username); ?>'s Profile - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main style="max-width: 900px; margin: auto;">
    <section class="profile-header" style="text-align: center; margin: 30px auto;">
        <img src="<?php echo htmlspecialchars($profile_picture ?: 'uploads/default.png'); ?>" 
             alt="Profile Picture" style="width: 120px; height: 120px; border-radius: 50%;">
        <h2><?php echo htmlspecialchars($username); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($bio)); ?></p>
        <?php if ($isOwner): ?>
            <a href="profile.php" class="edit-button">Edit Your Profile</a>
        <?php endif; ?>
    </section>

    <section>
        <h3>Listings by <?php echo htmlspecialchars($username); ?></h3>
        <?php if (empty($listings)): ?>
            <p>No listings created yet.</p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                    <p><?php echo htmlspecialchars($listing['description']); ?></p>
                    <?php if ($listing['image']): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image" style="max-width: 100%;">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section style="margin-top: 30px;">
        <h3>Threads by <?php echo htmlspecialchars($username); ?></h3>
        <?php if (empty($threads)): ?>
            <p>No threads started yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($threads as $thread): ?>
                    <li><a href="thread.php?id=<?php echo $thread['id']; ?>"><?php echo htmlspecialchars($thread['title']); ?></a>
                        <small>• <?php echo $thread['created_at']; ?></small></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can</p>
</footer>
</body>
</html>
