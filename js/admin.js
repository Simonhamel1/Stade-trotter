// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les boutons de changement de statut
    const toggleButtons = document.querySelectorAll('.toggle-status');
    
    // Ajouter un écouteur d'événement à chaque bouton
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const statusType = this.getAttribute('data-status-type'); // 'vip' ou 'banni'
            const currentStatus = this.getAttribute('data-current-status') === 'true';
            const row = this.closest('tr');
            
            // Trouver tous les utilisateurs dans le JSON
            fetch('../data/utilisateurs.json')
                .then(response => response.json())
                .then(users => {
                    // Trouver l'utilisateur correspondant
                    const userIndex = users.findIndex(user => user.Id === userId);
                    
                    if (userIndex !== -1) {
                        // Mettre à jour le statut
                        if (statusType === 'vip') {
                            users[userIndex].VIP = !currentStatus;
                        } else if (statusType === 'banni') {
                            users[userIndex].banni = !currentStatus;
                        }
                        
                        // Envoyer la mise à jour au serveur via PHP
                        return fetch('save_json.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(users)
                        });
                    }
                })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        // Mettre à jour l'affichage du bouton et du texte
                        const newStatus = !currentStatus;
                        this.setAttribute('data-current-status', newStatus.toString());
                        
                        // Mettre à jour le texte du statut
                        const statusCell = this.closest('td');
                        const statusText = statusCell.querySelector('.status-text');
                        statusText.textContent = newStatus ? 'OUI' : 'NON';
                        
                        // Mettre à jour l'apparence et le texte du bouton
                        if (newStatus) {
                            this.textContent = statusType === 'vip' ? 'Retirer VIP' : 'Débannir';
                            this.classList.remove('status-inactive');
                            this.classList.add('status-active');
                        } else {
                            this.textContent = statusType === 'vip' ? 'Passer VIP' : 'Bannir';
                            this.classList.remove('status-active');
                            this.classList.add('status-inactive');
                        }
                    } else {
                        alert('Erreur lors de la mise à jour: ' + result);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la mise à jour du statut.');
                });
        });
    });
});