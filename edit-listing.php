<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$currentUserId = $_SESSION['user_id'];
$listing_id = $_GET['id'] ?? null;

if (!$listing_id) {
    die("Invalid listing ID.");
}

// Fetch listing to pre-fill form
$stmt = $conn->prepare("SELECT l.title, l.description, l.price, l.image, l.skill_id, l.user_id 
                        FROM listings l WHERE l.id = ?");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$stmt->bind_result($title, $description, $price, $image, $skill_id, $owner_id);
$stmt->fetch();
$stmt->close();

if ($currentUserId !== $owner_id) {
    die("Unauthorized: You do not own this listing.");
}

// Fetch skills for dropdown
$skills = [];
$skill_query = $conn->prepare("SELECT id, skill_name FROM skills ORDER BY skill_name ASC");
$skill_query->execute();
$skill_query->bind_result($skill_db_id, $skill_name);
while ($skill_query->fetch()) {
    $skills[] = ['id' => $skill_db_id, 'name' => $skill_name];
}
$skill_query->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = htmlspecialchars(trim($_POST['title']));
    $new_description = htmlspecialchars(trim($_POST['description']));
    $new_skill_id = intval($_POST['skill_id']);
    $new_price = floatval($_POST['price']);

    $newImagePath = $image; // Default to old image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $newImagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath);
    }

    // Update the listing
    $update_stmt = $conn->prepare("UPDATE listings SET title=?, description=?, price=?, image=?, skill_id=? WHERE id=?");
    $update_stmt->bind_param("ssdssi", $new_title, $new_description, $new_price, $newImagePath, $new_skill_id, $listing_id);

    if ($update_stmt->execute()) {
        header('Location: show-listings.php');
        exit;
    } else {
        echo "<script>alert('Failed to update listing');</script>";
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="form-container">
        <h2>Edit Your Listing</h2>
        <form action="edit-listing.php?id=<?php echo $listing_id; ?>" method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>

            <label>Skill Category:</label>
            <select name="skill_id" required>
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo $skill['id']; ?>" <?php if ($skill['id'] == $skill_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($skill['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>

            <label>Current Image:</label><br>
            <?php if ($image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Current Image" style="max-width: 200px;"><br>
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>

            <label>Upload New Image (optional):</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Save Changes</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
