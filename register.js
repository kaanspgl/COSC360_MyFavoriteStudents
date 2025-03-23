document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register-form");

    form.addEventListener("submit", function (e) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert("Passwords do not match!");
            return false;
        }

        if (password.length < 6) {
            e.preventDefault();
            alert("Password must be at least 6 characters long.");
            return false;
        }

        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const profilePicture = document.getElementById("profile-picture").files[0];

        if (username.length < 3) {
            e.preventDefault();
            alert("Username must be at least 3 characters long.");
            return false;
        }

        if (!email.includes("@") || !email.includes(".")) {
            e.preventDefault();
            alert("Please enter a valid email address.");
            return false;
        }

        if (!profilePicture) {
            e.preventDefault();
            alert("Please upload a profile picture.");
            return false;
        }
    });
});
