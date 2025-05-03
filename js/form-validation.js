
document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(loginForm);
            
            fetch('../php/session/identification.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || 'acceuil.php';
                } else {
                    showPopup(data.message);
                }
            })
            .catch(error => {
                showPopup("Une erreur s'est produite lors de la connexion.");
                console.error('Error:', error);
            });
        });
        
        function showPopup(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }
    });
    
    function closePopup() {
        document.getElementById('errorPopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }