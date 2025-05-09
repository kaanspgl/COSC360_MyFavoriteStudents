<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="profile.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="profile-form-container">
            <h2>Edit Your Profile</h2>
            <form id="profile-form" action="profile.php" method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">

                <label for="profile-picture">Profile Picture:</label>
                <input type="file" id="profile-picture" name="profile-picture" accept="image/*">

                <button type="submit">Update Profile</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
</body>
</html>
