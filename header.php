<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Will be true if logged in
?>

<header>
    <div class="logo-container">
        <img src="images/logo.png" alt="I Can You Can Logo" class="logo">
    </div>
    <h1>I Can / You Can</h1>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="browse.php">Browse Skills</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="create-listing.php">Create Listing</a></li>
            <li><a href="show-listings.php">Show Listings</a></li>
            <li><a href="discussion.php">Discussions</a></li>
        </ul>
        <div class="auth-links">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php" class="profile-icon">
                    <img src="images/user.png" alt="My Profile">
                </a>
                <a href="logout.php" class="nav-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="profile-icon">
                    <img src="images/user.png" alt="Register/Login">
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>
