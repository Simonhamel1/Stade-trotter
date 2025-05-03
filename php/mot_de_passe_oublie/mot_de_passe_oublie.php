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
    <?php
// Vérification du cookie de thème au début du fichier
$theme = 'dark'; // Thème par défaut
if(isset($_COOKIE['theme'])) {
    // Validation de la valeur du cookie
    if($_COOKIE['theme'] === 'light' || $_COOKIE['theme'] === 'dark') {
        $theme = $_COOKIE['theme'];
    } else {
        // Valeur invalide, réinitialisation à la valeur par défaut
        setcookie('theme', $theme, time() + (365 * 24 * 60 * 60), '/');
    }
} else {
    // Le cookie n'existe pas, création avec la valeur par défaut
    setcookie('theme', $theme, time() + (365 * 24 * 60 * 60), '/');
}
?>
<header>
    <script>
        // Application immédiate du thème depuis le cookie pour éviter un affichage incorrect
        document.documentElement.setAttribute('data-theme', '<?php echo $theme; ?>');
    </script>
    <script src="../../js/bouton_changement.js" defer></script>
</header>

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
    
    // Vérification des paramètres URL qui pourraient contenir des messages de succès ou d'erreur
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
