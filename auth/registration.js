document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");

    if (!form) {
        console.error("Form element not found!");
        return;
    }

    form.addEventListener("submit", function (event) {
        // event.preventDefault();
        const name = document.getElementById("names").value.trim();
        const email = document.getElementById("emails").value.trim();
        const password = document.getElementById("pass").value;

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

        if (name === "" || email === "" || password === "") {
            alert("All fields are required!");
            return;
        }

        if (!passwordRegex.test(password)) {
            alert("Password must be at least 6 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.");
            return;
        }

        console.log("User Details:");
        console.log("Name:", name);
        console.log("Email:", email);
        console.log("Password:", password);

        alert("Form submitted successfully!");
    });
});
