<?php
    // Only reset session if user is not already logged in
    session_start();
    if (!isset($_SESSION['user_id'])) {
        session_unset();
        session_destroy();
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Football Club</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="../js/navbar.js"></script>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .popup-error { color: red; }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
     <!-- Entête navbar -->
     <?php include './header.php'; ?>
    
    <section class="form-container">
        <img src="../photo/logo.png" alt="Football Club Logo" id="form_logo" >
        <h2>⚽ Connexion ⚽</h2>
        
        <form id="loginForm" action="../php/session/identification.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="Email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="Password" required>

            <input type="submit" value="Se connecter">
            <p>Pas encore inscrit ? <a href="inscription.php">Créer un compte</a></p>
        </form>
    </section>
    
    <!-- Popup for errors -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="errorPopup">
        <p id="errorMessage" class="popup-error"></p>
        <button onclick="closePopup()">Fermer</button>
    </div>
    
    <script>
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
                    // Login successful, redirect
                    window.location.href = data.redirect || 'index.php';
                } else {
                    // Show error popup
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
    </script>
</body>
</html>
