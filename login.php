<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="login.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="profile-form-container">
            <h2>Login to Your Account</h2>
            <form id="login-form" action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">

                <button type="submit">Login</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
</body>
</html>
