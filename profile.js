document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("profile-form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission for now

        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const profilePicture = document.getElementById("profile-picture").files[0];

        if (username.length < 3) {
            alert("Username must be at least 3 characters long.");
            return;
        }

        if (!email.includes("@") || !email.includes(".")) {
            alert("Please enter a valid email address.");
            return;
        }

        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return;
        }

        if (!profilePicture) {
            alert("Please upload a profile picture.");
            return;
        }

        alert("Profile created successfully!");
    });
});
