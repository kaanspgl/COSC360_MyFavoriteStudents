<?php 
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login Success: Start session
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.');</script>";
    }
    $stmt->close();
}
?>
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

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
    </footer>
</body>
</html>
