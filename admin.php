<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}

$currentAdminId = $_SESSION['user_id'];
$searchQuery = $_GET['query'] ?? null;

// Initialize variables
$listings = [];
$logs = [];
$userFilterMsg = null;
$stmt = null;

// Search logic
if ($searchQuery) {
    $search = '%' . $searchQuery . '%';
    $userQuery = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ? OR email LIKE ? LIMIT 1");
    $userQuery->bind_param("ss", $search, $search);
    $userQuery->execute();
    $userQuery->bind_result($foundUserId, $foundUsername);
    $hasUser = $userQuery->fetch();
    $userQuery->close();

    if ($hasUser) {
        $stmt = $conn->prepare("SELECT listings.id, listings.title, listings.description, listings.image, listings.price, users.username, skills.skill_name 
                                FROM listings
                                JOIN users ON listings.user_id = users.id
                                JOIN skills ON listings.skill_id = skills.id
                                WHERE listings.user_id = ?
                                ORDER BY listings.id DESC");
        $stmt->bind_param("i", $foundUserId);
        $userFilterMsg = "Showing listings posted by <strong>" . htmlspecialchars($foundUsername) . "</strong>";
    } else {
        $userFilterMsg = "<em>No user found matching your search.</em>";
    }
}

if (!$stmt) {
    $stmt = $conn->prepare("SELECT listings.id, listings.title, listings.description, listings.image, listings.price, users.username, skills.skill_name 
                            FROM listings
                            JOIN users ON listings.user_id = users.id
                            JOIN skills ON listings.skill_id = skills.id
                            ORDER BY listings.id DESC");
}

$stmt->execute();
$stmt->bind_result($listing_id, $title, $description, $image, $price, $username, $skill_name);
while ($stmt->fetch()) {
    $listings[] = [
        'id' => $listing_id,
        'title' => $title,
        'description' => $description,
        'image' => $image,
        'price' => $price,
        'username' => $username,
        'skill' => $skill_name
    ];
}
$stmt->close();

// Fetch admin logs
$logResult = $conn->query("SELECT * FROM admin_logs ORDER BY timestamp DESC");
if ($logResult && $logResult->num_rows > 0) {
    while ($log = $logResult->fetch_assoc()) {
        $logs[] = $log;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="form-container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>. You have administrative privileges.</p>

        <?php
        // Admin stats queries
        $userCount     = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
        $listingCount  = $conn->query("SELECT COUNT(*) FROM listings")->fetch_row()[0];
        $commentCount  = $conn->query("SELECT COUNT(*) FROM listing_comments")->fetch_row()[0];
        $threadCount = $conn->query("SELECT COUNT(*) FROM discussion_threads")->fetch_row()[0];
        $logCount      = $conn->query("SELECT COUNT(*) FROM admin_logs")->fetch_row()[0];
        ?>

        <!-- Admin quick stats -->
        <div class="admin-stats" style="margin-top: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ccc; border-radius: 10px;">
            <h3 style="margin-top: 0;">Site Statistics</h3>
            <ul style="list-style: none; padding-left: 0;">
                <li><strong>Total Users:</strong> <?php echo $userCount; ?></li>
                <li><strong>Total Listings:</strong> <?php echo $listingCount; ?></li>
                <li><strong>Total Comments:</strong> <?php echo $commentCount; ?></li>
                <li><strong>Total Threads:</strong> <?php echo $threadCount; ?></li>
                <li><strong>Total Admin Actions Logged:</strong> <?php echo $logCount; ?></li>
            </ul>
        </div>


        <!-- Chart Container -->
        <div style="max-width: 800px; margin: 40px auto;">
            <h3 style="text-align: center;">Listings Created Per User</h3>
            <canvas id="listingChart"></canvas>
        </div>

        <!-- Chart.js Script -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch("listings-chart-data.php")
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('listingChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.usernames,
                            datasets: [{
                                label: 'Number of Listings',
                                data: data.counts,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
        });
        </script>

        <!-- Pie Chart Container -->
        <div style="max-width: 600px; margin: 50px auto;">
            <h3 style="text-align: center;">Listings by Skill Category</h3>
            <canvas id="skillChart"></canvas>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch("skill-chart-data.php")
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('skillChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.skills,
                            datasets: [{
                                data: data.counts,
                                backgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#8BC34A', '#E91E63',
                                    '#FF9800', '#009688', '#3F51B5', '#795548', '#9C27B0'
                                ],
                                borderColor: '#fff',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                });
        });
        </script>


        <!-- Search form -->
        <form method="GET" style="margin-top: 20px;">
            <input type="text" name="query" placeholder="Search by username or email" value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>">
            <button type="submit">Search</button>
        </form>

        <?php if ($userFilterMsg) echo "<p style='margin-top: 10px;'>$userFilterMsg</p>"; ?>
    </section>

    <section class="listings-container">
        <h3><?php echo isset($searchQuery) && isset($foundUserId) ? "Filtered Listings" : "All Listings"; ?></h3>
        <?php if (empty($listings)): ?>
            <p>No listings found.</p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                    <p><strong>Skill:</strong> <?php echo htmlspecialchars($listing['skill']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($listing['description']); ?></p>
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($listing['username']); ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($listing['price'], 2); ?></p>
                    <?php if (!empty($listing['image'])): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image" style="max-width:200px;"><br>
                    <?php endif; ?>
                    <a href="delete-listing.php?id=<?php echo $listing['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this listing?');"
                       style="color: white; background-color: red; padding: 5px 10px; display: inline-block; text-decoration: none; margin-top: 10px;">
                        Delete
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="listings-container">
        <h3>Admin Action Logs</h3>
        <?php if (empty($logs)): ?>
            <p>No admin actions logged yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($logs as $log): ?>
                    <li>
                        <strong><?php echo $log['timestamp']; ?>:</strong>
                        Admin ID <?php echo $log['admin_id']; ?> performed 
                        <strong><?php echo $log['action_type']; ?></strong> on 
                        <?php echo $log['target_type']; ?> ID 
                        <?php echo $log['target_id']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p style="text-align: center;">&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>
</body>
</html>
