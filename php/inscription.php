<?php
    session_start();
    // On conserve la session pour garder les données du formulaire
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
        
        <form action="./session/enregistrement.php" method="post">
            <!-- Protection CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(bin2hex(random_bytes(32))); ?>">
            
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="Prenom" 
                value="<?php echo isset($_SESSION['form_prenom']) ? htmlspecialchars($_SESSION['form_prenom']) : ''; ?>" 
                required>

            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="Nom" 
                value="<?php echo isset($_SESSION['form_nom']) ? htmlspecialchars($_SESSION['form_nom']) : ''; ?>" 
                required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="Email" 
                value="<?php echo isset($_SESSION['form_email']) ? htmlspecialchars($_SESSION['form_email']) : ''; ?>" 
                required>

            <label for="sexe">Sexe:</label>
            <select name="Sexe" id="sexe">
                <option value="Homme" <?php echo (isset($_SESSION['form_sexe']) && $_SESSION['form_sexe'] == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                <option value="Femme" <?php echo (isset($_SESSION['form_sexe']) && $_SESSION['form_sexe'] == 'Femme') ? 'selected' : ''; ?>>Femme</option>
            </select>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="Password" required>

            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="ConfirmPassword" required>

            <label for="club">Club préféré:</label>
            <select id="club" name="Club">
                <option value="PSG" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'PSG') ? 'selected' : ''; ?>>PSG</option>
                <option value="Real Madrid" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Real Madrid') ? 'selected' : ''; ?>>Real Madrid</option>
                <option value="Barcelone" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Barcelone') ? 'selected' : ''; ?>>FC Barcelone</option>
                <option value="Bayern" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Bayern') ? 'selected' : ''; ?>>Bayern Munich</option>
                <option value="Autre" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Autre') ? 'selected' : ''; ?>>Autre</option>
            </select>

            <input type="submit" value="S'inscrire">
            <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        </form>
    </section>

    <!-- Popup pour les messages d'erreur -->
    <div id="email-error-popup" class="popup2" <?php echo isset($_SESSION['error_message']) ? 'style="display: block;"' : ''; ?>>
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <p id="popup-message"><?php echo isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message']) : ''; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    </div>

    <script src="../js/inscription.js"></script>
</body>
</html>