document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("profile-form");

    form.addEventListener("submit", function (e) {
        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
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

        if (password.length < 6) {
            e.preventDefault();
            alert("Password must be at least 6 characters long.");
            return false;
        }

        if (!profilePicture) {
            e.preventDefault();
            alert("Please upload a profile picture.");
            return false;
        }
    });
});
