// Fonction pour gérer la modification des champs utilisateur
function modification_utilisateurs(buttonId) {
    // Récupérer l'élément input correspondant au bouton
    const inputId = 'input' + buttonId;
    const inputElement = document.getElementById(inputId);

    // Récupérer le conteneur du bouton
    const buttonContainer = document.getElementById(buttonId);

    // Récupérer le bouton "Modifier"
    const modifyButton = buttonContainer.querySelector('.permettre_modifications');

    // Si l'input est en lecture seule, le rendre modifiable
    if (inputElement.readOnly || inputElement.disabled) {
        // Sauvegarder la valeur originale
        inputElement.setAttribute('data-original-value', inputElement.value);

        // Rendre l'input modifiable
        if (inputElement.tagName.toLowerCase() === 'select') {
            inputElement.disabled = false;
        } else {
            inputElement.readOnly = false;
        }

        // Mettre en évidence l'input
        inputElement.classList.add('editing');
        // Ajouter une classe pour indiquer que le champ est en cours d'édition, mais pas encore validé
        inputElement.classList.add('not-validated');
        inputElement.focus();

        // Cacher le bouton "Modifier"
        modifyButton.style.display = 'none';

        // Ajouter les boutons "Valider" et "Annuler"
        const actionButtons = document.createElement('div');
        actionButtons.className = 'action-buttons';
        actionButtons.innerHTML = `
            <button type="button" class="validate-button" onclick="validerModification('${buttonId}')">
                Valider
            </button>
            <button type="button" class="cancel-button" onclick="annulerModification('${buttonId}')">
                Annuler
            </button>
        `;
        buttonContainer.appendChild(actionButtons);
    }
}
var modification_tab = [];

// Fonction pour valider la modification d'un champ
function validerModification(buttonId) {
    // Récupérer l'élément input
    const inputId = 'input' + buttonId;
    const inputElement = document.getElementById(inputId);

    // Récupérer le conteneur du bouton
    const buttonContainer = document.getElementById(buttonId);

    // Récupérer le bouton "Modifier"
    const modifyButton = buttonContainer.querySelector('.permettre_modifications');

    // Remettre l'input en lecture seule
    if (inputElement.tagName.toLowerCase() === 'select') {
        inputElement.disabled = true;
    } else {
        inputElement.readOnly = true;
    }

    // Enlever la classe indiquant que le champ n'était pas encore validé
    inputElement.classList.remove('not-validated');
    const key = inputElement.name;
    const value = inputElement.value;
    modification_tab.push(key + "=" + value);

    // Ajouter la classe indiquant que le champ a été validé et est prêt à être soumis
    inputElement.classList.add('validated');

    // Enlever la mise en évidence d'édition
    inputElement.classList.remove('editing');

    // Supprimer les boutons d'action
    const actionButtons = buttonContainer.querySelector('.action-buttons');
    if (actionButtons) {
        buttonContainer.removeChild(actionButtons);
    }

    // Afficher à nouveau le bouton "Modifier"
    modifyButton.style.display = 'block';

    // Vérifier si des champs ont été validés pour afficher le bouton de soumission
    checkValidatedFields();
}

// Fonction pour annuler la modification d'un champ
function annulerModification(buttonId) {
    // Récupérer l'élément input
    const inputId = 'input' + buttonId;
    const inputElement = document.getElementById(inputId);

    // Restaurer la valeur originale
    const originalValue = inputElement.getAttribute('data-original-value');
    if (originalValue !== null) {
        inputElement.value = originalValue;
    }

    // Récupérer le conteneur du bouton
    const buttonContainer = document.getElementById(buttonId);

    // Récupérer le bouton "Modifier"
    const modifyButton = buttonContainer.querySelector('.permettre_modifications');

    // Remettre l'input en lecture seule
    if (inputElement.tagName.toLowerCase() === 'select') {
        inputElement.disabled = true;
    } else {
        inputElement.readOnly = true;
    }

    // Enlever toutes les classes d'état
    inputElement.classList.remove('editing');
    inputElement.classList.remove('not-validated');
    inputElement.classList.remove('validated');

    // Supprimer les boutons d'action
    const actionButtons = buttonContainer.querySelector('.action-buttons');
    if (actionButtons) {
        buttonContainer.removeChild(actionButtons);
    }

    // Afficher à nouveau le bouton "Modifier"
    modifyButton.style.display = 'block';

    // Vérifier si des champs validés restent pour maintenir le bouton de soumission visible
    checkValidatedFields();
}

// Fonction pour soumettre les modifications
function soumettre_modification() {
    const serv = "http://localhost/";
    // Récupérer tous les champs validés
    const modification_string = modification_tab.join('&');

    // Afficher un indicateur de chargement
    const submitButton = document.getElementById('soumettre_button');
    if (submitButton) {
        submitButton.innerHTML = 'Mise à jour...';
        submitButton.disabled = true;
    }

    // Envoyer les données au serveur via fetch API
    window.fetch(new Request(serv + "StadeTrotter/php/modification_profil.php?" + modification_string))
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la soumission');
            }
            return response.json();
        })
        .then((data) => {
            // Afficher un message de succès ou d'erreur
            if (data.success) {
                // Notification de succès
                alert(data.message || 'Profil mis à jour avec succès');

                // Réinitialiser le tableau des modifications
                modification_tab = [];

                // Supprimer les classes de validation
                document.querySelectorAll('input.validated, select.validated').forEach(element => {
                    element.classList.remove('validated');
                });

                // Cacher le bouton de soumission
                if (submitButton) {
                    submitButton.style.display = 'none';
                }
            } else {
                // Notification d'erreur
                alert(data.message || 'Erreur lors de la mise à jour du profil');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la mise à jour du profil');
        })
        .finally(() => {
            // Remettre le bouton dans son état initial
            if (submitButton) {
                submitButton.innerHTML = 'Soumettre les modifications';
                submitButton.disabled = false;
            }
        });
}

// Fonction pour vérifier si au moins un champ a été validé
function checkValidatedFields() {
    const validatedFields = document.querySelectorAll('input.validated, select.validated');
    const submitButton = document.getElementById('soumettre_button');

    // Afficher ou masquer le bouton de soumission en fonction des champs validés
    if (validatedFields.length > 0) {
        submitButton.style.display = 'block';
    } else {
        submitButton.style.display = 'none';
    }
}

// Initialiser les gestionnaires d'événements quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function () {
    // S'assurer que le bouton de soumission est masqué au chargement
    const submitButton = document.getElementById('soumettre_button');
    if (submitButton) {
        submitButton.style.display = 'none';
    }

    // Vérifier s'il y a des champs déjà validés (lors d'un rechargement de page)
    checkValidatedFields();
});