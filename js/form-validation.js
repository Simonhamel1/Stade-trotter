document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne le formulaire (connexion ou inscription)
    const loginForm = document.querySelector('form');
    
    if (loginForm) {
        // Créer un élément pour afficher les messages d'erreur avec un style amélioré
        const errorPopup = document.createElement('div');
        errorPopup.className = 'error-popup';
        errorPopup.style.display = 'none';
        errorPopup.style.position = 'fixed';
        errorPopup.style.top = '20%';
        errorPopup.style.left = '50%';
        errorPopup.style.transform = 'translateX(-50%)';
        errorPopup.style.backgroundColor = '#f8d7da';
        errorPopup.style.color = '#721c24';
        errorPopup.style.padding = '15px 20px';
        errorPopup.style.borderRadius = '5px';
        errorPopup.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
        errorPopup.style.zIndex = '1000';
        errorPopup.style.maxWidth = '80%';
        errorPopup.style.textAlign = 'center';
        errorPopup.style.transition = 'all 0.3s ease';
        
        // Bouton de fermeture amélioré
        const closeButton = document.createElement('button');
        closeButton.textContent = '×';
        closeButton.style.position = 'absolute';
        closeButton.style.top = '5px';
        closeButton.style.right = '5px';
        closeButton.style.background = 'none';
        closeButton.style.border = 'none';
        closeButton.style.fontSize = '20px';
        closeButton.style.cursor = 'pointer';
        closeButton.style.color = '#721c24';
        
        errorPopup.appendChild(closeButton);
        document.body.appendChild(errorPopup);
        
        closeButton.addEventListener('click', function() {
            errorPopup.style.opacity = '0';
            setTimeout(() => errorPopup.style.display = 'none', 300);
        });
        
        // Validation côté client avant envoi
        function validateForm() {
            let isValid = true;
            let errorMessage = "";
            
            // Récupérer les champs du formulaire
            const emailField = loginForm.querySelector('input[type="Email"], input[name="Email"]');
            const passwordField = loginForm.querySelector('input[type="Password"], input[name="Password"]');
            
            // Réinitialiser les styles d'erreur
            if (emailField) emailField.style.border = '';
            if (passwordField) passwordField.style.border = '';
            
            // Valider l'email
            if (emailField && !emailField.value.trim()) {
                isValid = false;
                errorMessage = "Veuillez saisir votre adresse email";
                emailField.style.border = '2px solid #dc3545';
                emailField.focus();
            } else if (emailField && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value.trim())) {
                isValid = false;
                errorMessage = "Format d'email invalide";
                emailField.style.border = '2px solid #dc3545';
                emailField.focus();
            }
            
            // Valider le mot de passe
            if (isValid && passwordField && !passwordField.value) {
                isValid = false;
                errorMessage = "Veuillez saisir votre mot de passe";
                passwordField.style.border = '2px solid #dc3545';
                passwordField.focus();
            }
            
            // Si formulaire d'inscription, vérifier les autres champs
            const isRegisterForm = loginForm.classList.contains('register-form') || 
                                  window.location.href.includes('inscription');
            
            if (isRegisterForm) {
                const prenomField = loginForm.querySelector('input[name="prenom"]');
                const nomField = loginForm.querySelector('input[name="nom"]');
                const confirmPasswordField = loginForm.querySelector('input[name="confirm_password"]');
                
                // Réinitialiser les styles
                if (prenomField) prenomField.style.border = '';
                if (nomField) nomField.style.border = '';
                if (confirmPasswordField) confirmPasswordField.style.border = '';
                
                // Valider prénom
                if (isValid && prenomField && !prenomField.value.trim()) {
                    isValid = false;
                    errorMessage = "Veuillez saisir votre prénom";
                    prenomField.style.border = '2px solid #dc3545';
                    prenomField.focus();
                }
                
                // Valider nom
                if (isValid && nomField && !nomField.value.trim()) {
                    isValid = false;
                    errorMessage = "Veuillez saisir votre nom";
                    nomField.style.border = '2px solid #dc3545';
                    nomField.focus();
                }
                
                // Valider confirmation de mot de passe
                if (isValid && confirmPasswordField && passwordField) {
                    if (!confirmPasswordField.value) {
                        isValid = false;
                        errorMessage = "Veuillez confirmer votre mot de passe";
                        confirmPasswordField.style.border = '2px solid #dc3545';
                        confirmPasswordField.focus();
                    } else if (confirmPasswordField.value !== passwordField.value) {
                        isValid = false;
                        errorMessage = "Les mots de passe ne correspondent pas";
                        confirmPasswordField.style.border = '2px solid #dc3545';
                        passwordField.style.border = '2px solid #dc3545';
                        confirmPasswordField.focus();
                    }
                }
            }
            
            return { isValid, errorMessage };
        }
        
        // Intercepter la soumission du formulaire
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Valider le formulaire côté client d'abord
            const validation = validateForm();
            if (!validation.isValid) {
                showErrorMessage(validation.errorMessage);
                return;
            }
            
            // Afficher indicateur de chargement
            const submitBtn = loginForm.querySelector('button[type="submit"], input[type="submit"]');
            const originalText = submitBtn ? submitBtn.textContent || submitBtn.value : 'Envoyer';
            if (submitBtn) {
                submitBtn.disabled = true;
                if (submitBtn.tagName === 'INPUT') {
                    submitBtn.value = 'Chargement...';
                } else {
                    submitBtn.textContent = 'Chargement...';
                }
            }
            
            const formData = new FormData(loginForm);
            
            fetch(loginForm.action, {
                method: loginForm.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    // Afficher des messages d'erreur adaptés
                    if (data.error === 'email_not_found') {
                        showErrorMessage("Adresse mail inconnue");
                        const emailField = loginForm.querySelector('input[type="email"], input[name="email"]');
                        if (emailField) emailField.style.border = '2px solid #dc3545';
                    } else if (data.error === 'invalid_password') {
                        showErrorMessage("Mot de passe incorrect");
                        const passwordField = loginForm.querySelector('input[type="password"]');
                        if (passwordField) passwordField.style.border = '2px solid #dc3545';
                    } else if (data.error === 'banned') {
                        showErrorMessage("Votre compte a été suspendu par l'administrateur");
                    } else if (data.error === 'email_exists') {
                        showErrorMessage("Cette adresse email est déjà utilisée");
                        const emailField = loginForm.querySelector('input[type="email"], input[name="email"]');
                        if (emailField) emailField.style.border = '2px solid #dc3545';
                    } else {
                        showErrorMessage(data.message || "Erreur d'authentification");
                    }
                } else {
                    // En cas de succès, afficher un message puis rediriger
                    showSuccessMessage("Connexion réussie! Redirection en cours...");
                    setTimeout(function() {
                        window.location.href = data.redirect || 'accueil.php';
                    }, 1000);
                }
            })
            .catch(error => {
                showErrorMessage("Erreur de connexion au serveur");
                console.error('Erreur:', error);
            })
            .finally(() => {
                // Restaurer le bouton
                if (submitBtn) {
                    submitBtn.disabled = false;
                    if (submitBtn.tagName === 'INPUT') {
                        submitBtn.value = originalText;
                    } else {
                        submitBtn.textContent = originalText;
                    }
                }
            });
        });
        
        // Fonction pour afficher un message d'erreur
        function showErrorMessage(message) {
            // Style pour erreur
            errorPopup.style.backgroundColor = '#f8d7da';
            errorPopup.style.color = '#721c24';
            errorPopup.style.borderLeft = '5px solid #dc3545';
            closeButton.style.color = '#721c24';
            
            // Mettre à jour le contenu
            while (errorPopup.childElementCount > 1) {
                errorPopup.removeChild(errorPopup.lastChild);
            }
            
            const messageElement = document.createElement('p');
            messageElement.textContent = message;
            messageElement.style.margin = '10px 0';
            messageElement.style.fontSize = '16px';
            
            errorPopup.appendChild(messageElement);
            
            // Afficher avec animation
            errorPopup.style.display = 'block';
            setTimeout(() => {
                errorPopup.style.opacity = '1';
            }, 10);
            
            // Masquer automatiquement après 5 secondes
            setTimeout(function() {
                errorPopup.style.opacity = '0';
                setTimeout(() => {
                    errorPopup.style.display = 'none';
                }, 300);
            }, 5000);
        }
        
        // Fonction pour afficher un message de succès
        function showSuccessMessage(message) {
            // Style pour succès
            errorPopup.style.backgroundColor = '#d4edda';
            errorPopup.style.color = '#155724';
            errorPopup.style.borderLeft = '5px solid #28a745';
            closeButton.style.color = '#155724';
            
            // Mettre à jour le contenu
            while (errorPopup.childElementCount > 1) {
                errorPopup.removeChild(errorPopup.lastChild);
            }
            
            const messageElement = document.createElement('p');
            messageElement.textContent = message;
            messageElement.style.margin = '10px 0';
            messageElement.style.fontSize = '16px';
            
            errorPopup.appendChild(messageElement);
            
            // Afficher avec animation
            errorPopup.style.display = 'block';
            setTimeout(() => {
                errorPopup.style.opacity = '1';
            }, 10);
        }
        
        // Réinitialiser les styles d'erreur lors de la saisie
        const formInputs = loginForm.querySelectorAll('input');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.style.border = '';
            });
        });
    }
});
