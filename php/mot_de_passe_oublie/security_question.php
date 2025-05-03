<?php
session_start();

// Vérifier si l'email et la question de sécurité sont définis dans la session
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['security_question'])) {
    header("Location: ../connexion.php");
    exit();
}

$email = $_SESSION['reset_email'];
$question = $_SESSION['security_question'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question de sécurité - Football Club</title>
    <link rel="stylesheet" href="../../css/form.css">
    <script src="../../js/navbar.js"></script>
</head>
<body>
    <?php
// Vérifier le cookie de thème au début du fichier
$theme = 'dark'; // Thème par défaut
if(isset($_COOKIE['theme'])) {
    // Valider la valeur du cookie
    if($_COOKIE['theme'] === 'light' || $_COOKIE['theme'] === 'dark') {
        $theme = $_COOKIE['theme'];
    } else {
        // Valeur invalide, réinitialiser à la valeur par défaut
        setcookie('theme', $theme, time() + (365 * 24 * 60 * 60), '/');
    }
} else {
    // Le cookie n'existe pas, le créer avec la valeur par défaut
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
            
                if(isset($_SESSION['user_id'])) {
                    if($_SESSION['user_id'] == "31a446ed3e48942499fa6eec61b14eca563dc2d7210ba41d3807407c3e1de0c2"){
                        echo '<li class="connexion"><a href="../admin.php">' . "ADMIN" . '</a></li>';
                    } else {
                        echo '<li class="connexion"><a href="../profil.php">' . $_SESSION["Prenom"] . '</a></li>';
                    }
                } else {
                    echo '<li class="connexion"><a href="../connexion.php">Connexion</a></li>';
                }
                ?>
                <li><a href="../panier.php">🛒 Panier</a></li>
            </ul>
        </nav>
    </div>
    <link rel="stylesheet" href="../../css/bouton_changement.css">
    <script>
        // Appliquer immédiatement le thème depuis le cookie pour éviter un flash du mauvais thème
        document.documentElement.setAttribute('data-theme', '<?php echo $theme; ?>');
    </script>
    <script src="../../js/bouton_changement.js" defer></script>
</header>

    
    <section class="form-container">
        <img src="../../photo/logo.png" alt="Football Club Logo" id="form_logo">
        <h2>⚽ Vérification de sécurité ⚽</h2>
        
        <form id="securityQuestionForm" action="./verify_security_answer.php" method="post">
            <p>Veuillez répondre à votre question de sécurité pour réinitialiser votre mot de passe.</p>
            
            <label for="question">Question de sécurité:</label>
            <input type="text" id="question" value="<?php echo htmlspecialchars($question); ?>" readonly>
            
            <label for="answer">Votre réponse:</label>
            <input type="text" id="answer" name="answer" required>
            
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" id="new_password" name="new_password" required>
            
            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <input type="submit" value="Réinitialiser le mot de passe">
            <p><a href="../connexion.php">Retour à la connexion</a></p>
        </form>
    </section>
    
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
    
    document.getElementById('securityQuestionForm').addEventListener('submit', function(event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            event.preventDefault();
            showPopup('Les mots de passe ne correspondent pas.', true);
        }
    });
    
    // Verification de la réponse à la question de sécurité si elle est vraie
    document.getElementById('securityQuestionForm').addEventListener('submit', function(event) {
        const answer = document.getElementById('answer').value;
        
        if (answer.trim() === '') {
            event.preventDefault();
            showPopup('Veuillez entrer une réponse à la question de sécurité.', true);
        }
    });
    
    // Vérifier les paramètres de l'URL
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