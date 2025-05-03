document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-status');
    
    toggleButtons.forEach(button => {
        //Pour ajouter l'élément spinner à chaque bouton
        const spinner = document.createElement('span');
        spinner.className = 'spinner';
        spinner.style.display = 'none';
        spinner.style.marginLeft = '5px';
        spinner.style.animation = 'spin 1s linear infinite';
        button.appendChild(spinner);
        
        button.addEventListener('click', function() {
            this.classList.add('disabled');
            
            // Afficher le spinner
            const spinner = this.querySelector('.spinner');
            spinner.style.display = 'inline-block';
            
            const userId = this.getAttribute('data-user-id');
            const statusType = this.getAttribute('data-status-type');
            const currentStatus = this.getAttribute('data-current-status') === 'true';
            
            fetch('../data/utilisateurs.json')
                .then(response => response.json())
                .then(users => {
                    const userIndex = users.findIndex(user => user.Id === userId);
                    
                    if (userIndex !== -1) {
                        if (statusType === 'vip') {
                            users[userIndex].VIP = !currentStatus;
                        } else if (statusType === 'banni') {
                            users[userIndex].banni = !currentStatus;
                        }
                        
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
                    // Ajouter un délai avant de cacher le spinner
                    setTimeout(() => {
                        this.classList.remove('disabled');
                        spinner.style.display = 'none';
                        
                        if (result === 'success') {
                            const newStatus = !currentStatus;
                            this.setAttribute('data-current-status', newStatus.toString());
                            
                            const statusCell = this.closest('td');
                            const statusText = statusCell.querySelector('.status-text');
                            statusText.textContent = newStatus ? 'OUI' : 'NON';
                            
                            if (newStatus) {
                                this.textContent = statusType === 'vip' ? 'Retirer VIP' : 'Débannir';
                                this.classList.remove('status-inactive');
                                this.classList.add('status-active');
                            } else {
                                this.textContent = statusType === 'vip' ? 'Passer VIP' : 'Bannir';
                                this.classList.remove('status-active');
                                this.classList.add('status-inactive');
                            }
                            // Réajoute le spinner après modification du texte
                            this.appendChild(spinner);
                        } else {
                            alert('Erreur lors de la mise à jour: ' + result);
                        }
                    }, 1500); // Affiche le spinner pendant 1.5 secondes supplémentaires
                })
                .catch(error => {
                    setTimeout(() => {
                        this.classList.remove('disabled');
                        spinner.style.display = 'none';
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la mise à jour du statut.');
                    }, 1000);
                });
        });
    });
    
    // Ajoute le style pour l'animation de rotation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spinner {
            display: inline-block;
            font-weight: bold;
            font-size: 1.2em;
        }
    `;
    document.head.appendChild(style);
});