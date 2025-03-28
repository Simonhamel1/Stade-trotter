<?php
    session_start();
    session_unset();
    session_destroy();
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Football Club</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="../js/navbar.js"></script>
</head>
<body>
    <!-- Entête navbar -->
    <?php include './header.php'; ?>
    
    <section class="form-container">
        <img src="../photo/logo.png" alt="Football Club Logo" id="form_logo" >
        <h2>⚽ Inscription ⚽</h2>
        <?php 
            if(isset($_SESSION["error_message"])){
            echo '<p style="color: red;">' . $_SESSION["error_message"] . '</p>';
            unset($_SESSION["error_message"]);
            }
        ?>
        <form action="./session/enregistrement.php" method="post">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="Prenom" required>

            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="Nom" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="Email" required>

            <label for="sexe">Sexe:</label>
            <select name="Sexe" id="sexe">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="Password" required>

            <label for="password">Confirmer le mot de passe:</label>
            <input type="password" id="password" name="Password" required>


            <label for="club">Club préféré:</label>
            <select id="club" name="Club">
                <option value="PSG">PSG</option>
                <option value="Real Madrid">Real Madrid</option>
                <option value="Barcelone">FC Barcelone</option>
                <option value="Bayern">Bayern Munich</option>
                <option value="Autre">Autre</option>
            </select>

            <input type="submit" value="S'inscrire">
            <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        </form>
    </section>
</body>
</html>
