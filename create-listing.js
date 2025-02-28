document.addEventListener("DOMContentLoaded", function () {
    // Simulating user authentication check (Replace with real auth check later)
    const isLoggedIn = false; // Change this to true to simulate a logged-in user

    if (!isLoggedIn) {
        alert("You must be logged in to create a listing. Redirecting to signup page...");
        window.location.href = "profile.html"; // Redirect to account creation page
    }
});
