<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit;
}

// Fetch skills dynamically
$skills = [];
$skill_query = $conn->prepare("SELECT id, skill_name FROM skills ORDER BY skill_name ASC");
$skill_query->execute();
$skill_query->bind_result($skill_id, $skill_name);
while ($skill_query->fetch()) {
    $skills[] = ['id' => $skill_id, 'name' => $skill_name];
}
$skill_query->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $skill_id = intval($_POST['skill_id']);
    $price = floatval($_POST['price']);
    $user_id = $_SESSION['user_id'];

    // Image upload (optional)
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Insert listing into database
    $stmt = $conn->prepare("INSERT INTO listings (user_id, skill_id, title, description, price, image) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissds", $user_id, $skill_id, $title, $description, $price, $imagePath);

    if ($stmt->execute()) {
        header('Location: show-listings.php');
        exit;
    } else {
        echo "<script>alert('Failed to create listing');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="form-container">
        <h2>Create a New Listing</h2>
        <form action="create-listing.php" method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Skill Category:</label>
            <select name="skill_id" required>
                <option value="">-- Select Skill --</option>
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo $skill['id']; ?>"><?php echo htmlspecialchars($skill['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Upload an Image (optional):</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Create Listing</button>
        </form>
    </section>
</main>


<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
