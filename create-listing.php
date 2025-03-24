<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="create-listing.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<<<<<<< Updated upstream

    <main>
        <section class="placeholder-container">
            <h2>Create a New Listing</h2>
            <p>This feature will allow users to create skill listings. Users must be logged in to access this page.</p>
            <p>If you're not logged in, you will be redirected to the account creation page.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
=======
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
>>>>>>> Stashed changes
</body>
</html>
