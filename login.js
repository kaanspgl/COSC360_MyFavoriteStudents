document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("login-form");

    form.addEventListener("submit", function (e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        if (!email.includes("@") || !email.includes(".")) {
            e.preventDefault();
            alert("Please enter a valid email address.");
            return;
        }

        if (password.length < 6) {
            e.preventDefault();
            alert("Password must be at least 6 characters long.");
            return;
        }
    });
});
