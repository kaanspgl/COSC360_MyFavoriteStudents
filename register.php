<?php
session_start();
include ('config.php');

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Basic validation
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $profilePicPath = '';
        if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024;

            $fileType = mime_content_type($_FILES['profile-picture']['tmp_name']);
            $fileSize = $_FILES['profile-picture']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                echo "<script>alert('Only JPG, PNG, or GIF images are allowed.');</script>";
                exit;
            }

            if ($fileSize > $maxSize) {
                echo "<script>alert('Profile picture must be less than 2MB.');</script>";
                exit;
            }

            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir);
            $profilePicPath = $targetDir . basename($_FILES['profile-picture']['name']);
            move_uploaded_file($_FILES['profile-picture']['tmp_name'], $profilePicPath);
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $profilePicPath);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: Could not register.');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="register.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="profile-form-container">
        <h2>Create Your Account</h2>
        <form id="register-form" action="register.php" method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="6">

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <label for="profile-picture">Profile Picture:</label>
            <input type="file" id="profile-picture" name="profile-picture" accept="image/*">

            <button type="submit">Register</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
