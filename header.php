<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$profilePicture = 'images/user.png';
$username = '';

if ($isLoggedIn) {
    include 'config.php';

    if (!$conn->connect_error) {
        $stmt = $conn->prepare("SELECT profile_picture, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($dbProfilePic, $dbUsername);
        if ($stmt->fetch()) {
            if (!empty($dbProfilePic)) {
                $profilePicture = $dbProfilePic;
            }
            $username = $dbUsername;
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="images/logo.png" alt="I Can You Can Logo" class="logo">
        </a>
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

            <?php if ($isLoggedIn): ?>
                <li><a href="profile.php">
                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="My Profile" class="nav-profile-icon">
                </a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">
                    <img src="images/user.png" alt="Login" class="nav-profile-icon">
                </a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
                <div class="welcome-msg">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
            <?php endif; ?>

        </ul>
    </nav>
</header>
