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
    <script src="../js/password_viewer.js"></script>
    <script src="../js/form-validation.js"></script>
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
            <input type="password" id="password" name="Password" class="toggle-password" required>

            <p><a href="./mot_de_passe_oublie/mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>

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
    

</body>
</html>
