/**
 * Version améliorée pour le basculement de visibilité des mots de passe
 */
document.addEventListener('DOMContentLoaded', function() {
    // Trouver tous les champs de mot de passe
    const passwordFields = document.querySelectorAll('input[type="password"]');
    
    passwordFields.forEach(function(passwordField) {
        // Créer un conteneur pour le champ et le bouton
        const container = document.createElement('div');
        container.style.position = 'relative';
        container.style.display = 'inline-block';
        
        // Remplacer le champ par le conteneur
        passwordField.parentNode.insertBefore(container, passwordField);
        container.appendChild(passwordField);
        
        // Créer le bouton de basculement
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'password-toggle-btn';
        toggleButton.innerHTML = '👁️'; // Emoji œil
        toggleButton.title = 'Afficher/Masquer le mot de passe';
        
        // Styles pour le bouton
        toggleButton.style.cssText = `
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            padding: 0;
            color: #333;
            z-index: 100;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        
        // Ajuster les styles du champ de mot de passe
        passwordField.style.paddingRight = '40px';
        passwordField.style.boxSizing = 'border-box';
        
        // Ajouter un gestionnaire d'événements pour le basculement
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher la soumission du formulaire
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.innerHTML = '🔒'; // Emoji cadenas fermé
                this.title = 'Masquer le mot de passe';
            } else {
                passwordField.type = 'password';
                this.innerHTML = '👁️'; // Emoji œil
                this.title = 'Afficher le mot de passe';
            }
        });
        
        // Ajouter le bouton au conteneur
        container.appendChild(toggleButton);
    });
});