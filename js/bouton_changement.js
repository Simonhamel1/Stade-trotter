document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const htmlElement = document.documentElement;
    
    // Applique le thème enregistré
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        htmlElement.setAttribute('data-theme', savedTheme);
    }
    
    toggleBtn.addEventListener('click', function() {
        // Désactive le bouton pendant l'attente
        toggleBtn.disabled = true;
        toggleBtn.classList.add('disabled');
        
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Applique le changement de thème immédiatement
        if (newTheme === 'light') {
            htmlElement.removeAttribute('data-theme');
        } else {
            htmlElement.setAttribute('data-theme', 'dark');
        }

        // Enregistre le thème
        localStorage.setItem('theme', newTheme);

        // Réactive le bouton
        toggleBtn.disabled = false;
        toggleBtn.classList.remove('disabled');
    });
});