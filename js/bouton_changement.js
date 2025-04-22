// theme.js
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const htmlElement = document.documentElement;
    
    // Appliquer le thème enregistré
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        htmlElement.setAttribute('data-theme', savedTheme);
    }
    
    toggleBtn.addEventListener('click', function() {
        // Désactiver le bouton pendant l'attente
        toggleBtn.disabled = true;
        toggleBtn.classList.add('disabled');
        
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Simuler une attente de 2 secondes
        setTimeout(function() {
            // Appliquer le changement de thème
            if (newTheme === 'light') {
                htmlElement.removeAttribute('data-theme');
            } else {
                htmlElement.setAttribute('data-theme', 'dark');
            }
            
            // Enregistrer le thème
            localStorage.setItem('theme', newTheme);
            
            // Réactiver le bouton
            toggleBtn.disabled = false;
            toggleBtn.classList.remove('disabled');
        }, 2000); // Délai de 2 secondes
    });
});