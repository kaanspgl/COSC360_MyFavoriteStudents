document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register-form");

    form.addEventListener("submit", function (e) {
        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
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

        if (!profilePicture) {
            e.preventDefault();
            alert("Please upload a profile picture.");
            return false;
        }

        const validTypes = ["image/jpeg", "image/png", "image/gif"];
        const maxSize = 2 * 1024 * 1024;

        if (!validTypes.includes(profilePicture.type)) {
            e.preventDefault();
            alert("Only JPG, PNG, or GIF images are allowed.");
            return false;
        }

        if (profilePicture.size > maxSize) {
            e.preventDefault();
            alert("Profile picture must be less than 2MB.");
            return false;
        }
    });
});
