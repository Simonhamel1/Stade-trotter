<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Football Club</title>
    <link rel="stylesheet" href="../../css/form.css">
    <script src="../../js/navbar.js"></script>
</head>
<body>
    <!-- Entête navbar -->
    <?php
// Check for theme cookie at the beginning of the file
$theme = 'dark'; // Default theme
if(isset($_COOKIE['theme'])) {
    // Validate cookie value
    if($_COOKIE['theme'] === 'light' || $_COOKIE['theme'] === 'dark') {
        $theme = $_COOKIE['theme'];
    } else {
        // Invalid value, reset to default
        setcookie('theme', $theme, time() + (365 * 24 * 60 * 60), '/');
    }
} else {
    // Cookie doesn't exist, create with default value
    setcookie('theme', $theme, time() + (365 * 24 * 60 * 60), '/');
}
?>
<header>
    <div class="navbar">
        <a href="#top">
            <img class="logo" src="../../photo/logo.png" alt="Logo Stade Trotter">
        </a>
        <nav>
            <ul>
                <li><a href="../accueil.php">Accueil</a></li>
                <li><a href="../destinations.php">Destinations</a></li>
                <li><a href="../a_propos.php">À propos</a></li>
                <li class="theme-toggle">
                    <button id="theme-toggle-btn" aria-label="Changer de thème" data-current-theme="<?php echo $theme; ?>">
                        <span class="theme-icon light">☀️</span>
                        <span class="theme-icon dark">🌙</span>
                    </button>
                </li>
                <?php
            
                if(isset($_SESSION['user'])) {
                    if($_SESSION['user'] == "31a446ed3e48942499fa6eec61b14eca563dc2d7210ba41d3807407c3e1de0c2"){
                        echo '<li class="connexion"><a href="../admin.php">' . "ADMIN" . '</a></li>';
                    } else {
                        echo '<li class="connexion"><a href="../profil.php">' . $_SESSION["Prenom"] . '</a></li>';
                    }
                } else {
                    echo '<li class="connexion"><a href="../connexion.php">Connexion</a></li>';
                }
                ?>
                <li><a href="panier.php">🛒 Panier</a></li>
            </ul>
        </nav>
    </div>
    <link rel="stylesheet" href="../../css/bouton_changement.css">
    <script>
        // Apply theme from cookie immediately to prevent flash of wrong theme
        document.documentElement.setAttribute('data-theme', '<?php echo $theme; ?>');
    </script>
    <script src="../../js/bouton_changement.js" defer></script>
</header>

    
    <section class="form-container">
        <img src="../../photo/logo.png" alt="Football Club Logo" id="form_logo" >
        <h2> Mot de passe oublié </h2>
        
        <form id="forgotPasswordForm" action="./check_email.php" method="post">
            <label for="email">Entrez votre email:</label>
            <input type="email" id="email" name="email" required>
            
            <input type="submit" value="Continuer">
            <p><a href="../connexion.php">Retour à la connexion</a></p>
        </form>
    </section>
    
    <!-- Popup for messages -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="messagePopup">
        <p id="popupMessage"></p>
        <button onclick="closePopup()">Fermer</button>
    </div>
    
    <script>
    function showPopup(message, isError = false) {
        document.getElementById('popupMessage').textContent = message;
        if (isError) {
            document.getElementById('popupMessage').className = 'popup-error';
        } else {
            document.getElementById('popupMessage').className = '';
        }
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('messagePopup').style.display = 'block';
    }
    
    function closePopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('messagePopup').style.display = 'none';
    }
    
    // Check for URL parameters that might contain success or error messages
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const success = urlParams.get('success');
        
        if (error) {
            showPopup(decodeURIComponent(error), true);
        } else if (success) {
            showPopup(decodeURIComponent(success), false);
        }
    };
    </script>
</body>
</html>