// prix-dynamique.js - Gestion dynamique des prix
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des éléments du DOM
    const nbParticipantsInput = document.getElementById('nb_participants');
    const prixBaseElement = document.getElementById('prix-base');
    const prixTotalElement = document.getElementById('prix-total');
    
    // Récupération du prix de base et du nombre initial de participants
    let prixBase = parseFloat(prixBaseElement.dataset.prix);
    let nbParticipants = parseInt(nbParticipantsInput.value);
    
    // Fonction pour mettre à jour le prix total
    function mettreAJourPrixTotal() {
        // Calcul initial avec le prix de base
        let prixTotal = prixBase * nbParticipants;
        
        // Récupération et calcul du prix des options sélectionnées
        document.querySelectorAll('select[name^="etapes"], input[type="checkbox"][name^="etapes"]:checked').forEach(element => {
            // Pour les select
            if (element.tagName === 'SELECT') {
                const selectedOption = element.options[element.selectedIndex];
                const prixOption = extrairePrix(selectedOption.textContent);
                if (prixOption) {
                    prixTotal += prixOption * nbParticipants;
                }
            } 
            // Pour les checkboxes
            else if (element.type === 'checkbox' && element.checked) {
                const labelText = element.parentElement.textContent;
                const prixOption = extrairePrix(labelText);
                if (prixOption) {
                    prixTotal += prixOption * nbParticipants;
                }
            }
        });
        
        // Mise à jour de l'affichage du prix total
        prixTotalElement.textContent = prixTotal.toFixed(2);
        
        // Mise à jour du champ caché pour le formulaire
        document.querySelector('input[name="total_price"]').value = prixTotal.toFixed(2);
    }
    
    // Fonction pour extraire le prix d'une chaîne "(XX €)"
    function extrairePrix(texte) {
        const regex = /\((\d+(?:\.\d+)?)\s*€\)/;
        const match = regex.exec(texte);
        return match ? parseFloat(match[1]) : 0;
    }
    
    // Écouteurs d'événements
    nbParticipantsInput.addEventListener('change', function() {
        nbParticipants = parseInt(this.value);
        mettreAJourPrixTotal();
    });
    
    // Écouteur pour tous les selects d'options
    document.querySelectorAll('select[name^="etapes"]').forEach(select => {
        select.addEventListener('change', mettreAJourPrixTotal);
    });
    
    // Écouteur pour toutes les checkboxes d'options
    document.querySelectorAll('input[type="checkbox"][name^="etapes"]').forEach(checkbox => {
        checkbox.addEventListener('change', mettreAJourPrixTotal);
    });
    
    // Calcul initial du prix total
    mettreAJourPrixTotal();
});