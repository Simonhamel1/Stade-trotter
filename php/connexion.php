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
    <title>Connexion - Football Club</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="../js/navbar.js"></script>
</head>
<body>
     <!-- Entête navbar -->
     <?php include './header.php'; ?>
    
    <section class="form-container">
        <img src="../photo/logo.png" alt="Football Club Logo" id="form_logo" >
        <h2>⚽ Connexion ⚽</h2>
        <?php 
            if(isset($_SESSION["error_message"])){
            echo '<p style="color: red;">' . $_SESSION["error_message"] . '</p>';
            unset($_SESSION["error_message"]);
            }
        ?>
        <form action="../php/session/identification.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="Email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="Password" required>

            <input type="submit" value="Se connecter">
            <p>Pas encore inscrit ? <a href="inscription.php">Créer un compte</a></p>
        </form>
    </section>
</body>
</html>
