
// Code JavaScript séparé du PHP
document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour fermer la popup
    const closeBtn = document.querySelector(".close-btn");
    const popup = document.getElementById("email-error-popup");

    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            popup.style.display = "none";
        });
    }

    // Fermer la popup en cliquant en dehors
    window.addEventListener('click', function (event) {
        if (event.target == popup) {
            popup.style.display = "none";
        }
    });

    // Validation des mots de passe
    document.querySelector("form").addEventListener("submit", function (e) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {
            e.preventDefault();
            document.getElementById("popup-message").textContent = "Les mots de passe ne correspondent pas";
            popup.style.display = "block";
        }
    });
});