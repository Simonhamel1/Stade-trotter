document.addEventListener("DOMContentLoaded", function () {
    let palette = document.querySelector(".palette");
    let loadingScreen = document.querySelector(".loading-screen");
    let contenu = document.querySelector(".contenu");

    function onMouseMove(event) {
        let mouseX = event.clientX;
        let mouseY = event.clientY;

        // Ajoute un délai avant de mettre à jour la position de la palette
        setTimeout(function() {
            palette.style.left = `${mouseX}px`;
            palette.style.top = `${mouseY}px`;
        }, 100); // Délai de 100 millisecondes
    }

    document.addEventListener("mousemove", onMouseMove);

    document.addEventListener("click", function () {
        document.removeEventListener("mousemove", onMouseMove);
        loadingScreen.classList.add("fade-out");

        setTimeout(() => {
            loadingScreen.style.display = "none";
            contenu.style.display = "block";
        }, 500);
    });
});
