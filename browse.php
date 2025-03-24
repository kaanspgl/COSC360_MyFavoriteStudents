<?php
session_start();
include 'config.php';

// Fetch all skills from the database
$skills = [];
$result = $conn->query("SELECT id, skill_name, skill_description FROM skills");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Skills - I Can / You Can</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="search-container">
        <input type="text" id="search" placeholder="Search for skills..." onkeyup="filterSkills()">
    </section>

    <section class="skills-list" id="skills-list">
        <?php foreach ($skills as $skill): ?>
            <div class="skill-card">
                <h3><?php echo htmlspecialchars($skill['skill_name']); ?></h3>
                <p><?php echo htmlspecialchars($skill['skill_description']); ?></p>
                <div class="card-footer">
                    <a href="show-listings.php?skill_id=<?php echo $skill['id']; ?>" class="learn-more">Learn Now!</a>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 I Can / You Can. All rights reserved.</p>
</footer>

<script>
// Simple search functionality
function filterSkills() {
    const input = document.getElementById("search").value.toLowerCase();
    const skills = document.getElementsByClassName("skill-card");

    Array.from(skills).forEach(skill => {
        const title = skill.querySelector("h3").textContent.toLowerCase();
        if (title.includes(input)) {
            skill.style.display = "block";
        } else {
            skill.style.display = "none";
        }
    });
}
</script>
</body>
</html>
