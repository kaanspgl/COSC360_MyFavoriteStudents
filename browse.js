function filterSkills() {
    const searchInput = document.getElementById("search").value.toLowerCase();
    const skillCards = document.querySelectorAll(".skill-card");

    skillCards.forEach(card => {
        const title = card.querySelector("h3").textContent.toLowerCase();
        const description = card.querySelector("p").textContent.toLowerCase();

        if (title.includes(searchInput) || description.includes(searchInput)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}
