<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Football Club</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="../js/navbar.js"></script>*
    <script src="../js/password_viewer.js"></script>
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
            <input type="password" id="password" name="Password" class="toggle-password" required>

            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="ConfirmPassword" class="toggle-password" required>

            <label for="club">Club préféré:</label>
            <select id="club" name="Club">
                <option value="PSG" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'PSG') ? 'selected' : ''; ?>>PSG</option>
                <option value="Real Madrid" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Real Madrid') ? 'selected' : ''; ?>>Real Madrid</option>
                <option value="Barcelone" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Barcelone') ? 'selected' : ''; ?>>FC Barcelone</option>
                <option value="Bayern" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Bayern') ? 'selected' : ''; ?>>Bayern Munich</option>
                <option value="Autre" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Autre') ? 'selected' : ''; ?>>Autre</option>
                <option value="Manchester United" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Manchester United') ? 'selected' : ''; ?>>Manchester United</option>
                <option value="Liverpool" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Liverpool') ? 'selected' : ''; ?>>Liverpool</option>
                <option value="Chelsea" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Chelsea') ? 'selected' : ''; ?>>Chelsea</option>
                <option value="Manchester City" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Manchester City') ? 'selected' : ''; ?>>Manchester City</option>
                <option value="Juventus" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Juventus') ? 'selected' : ''; ?>>Juventus</option>
                <option value="AC Milan" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'AC Milan') ? 'selected' : ''; ?>>AC Milan</option>
                <option value="Borussia Dortmund" <?php echo (isset($_SESSION['form_club']) && $_SESSION['form_club'] == 'Borussia Dortmund') ? 'selected' : ''; ?>>Borussia Dortmund</option>
            </select>

            <label for="security_question">Question de sécurité:</label>
            <select id="security_question" name="Question" required>
                <option value="">Sélectionnez une question</option>
                <option value="Nom de votre premier animal">Nom de votre premier animal</option>
                <option value="Nom de jeune fille de votre mère">Nom de jeune fille de votre mère</option>
                <option value="Ville de naissance">Ville de naissance</option>
                <option value="Premier emploi">Premier emploi</option>
                <option value="Modèle de votre première voiture">Modèle de votre première voiture</option>
            </select>
            
            <label for="security_answer">Réponse:</label>
            <input type="text" id="security_answer" name="Reponse" required>

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