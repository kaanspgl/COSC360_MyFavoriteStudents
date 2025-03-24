<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$profilePicture = $_SESSION['profile_picture'] ?? 'images/user.png';

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

        <!-- Breadcrumbs -->
    <div class="breadcrumb-container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <?php 
            $currentPage = basename($_SERVER['PHP_SELF']);
            if ($currentPage === 'show-listings.php') {
                echo ' &raquo; <span>Listings</span>';
            } elseif ($currentPage === 'discussion.php') {
                echo ' &raquo; <span>Discussions</span>';
            } elseif ($currentPage === 'thread.php') {
                echo ' &raquo; <a href="discussion.php">Discussions</a> &raquo; <span>Thread</span>';
            } elseif ($currentPage === 'create-listing.php') {
                echo ' &raquo; <span>Create Listing</span>';
            } elseif ($currentPage === 'profile.php') {
                echo ' &raquo; <span>Profile</span>';
            } elseif ($currentPage === 'about.php') {
                echo ' &raquo; <span>About Us</span>';
            } elseif ($currentPage === 'browse.php') {
                echo ' &raquo; <span>Browse Skills</span>';
            }
            ?>
        </div>
    </div>


</header>
