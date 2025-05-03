<?php
// Vérifie le cookie de thème au début du fichier
$theme = 'dark'; // Thème par défaut
if(isset($_COOKIE['theme'])) {
    // Valide la valeur du cookie
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
    <div class="navbar">
        <a href="#top">
            <img class="logo" src="../photo/logo.png" alt="Logo Stade Trotter">
        </a>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="destinations.php">Destinations</a></li>
                <li><a href="a_propos.php">À propos</a></li>
                <li class="theme-toggle">
                    <button id="theme-toggle-btn" aria-label="Changer de thème" data-current-theme="<?php echo $theme; ?>">
                        <span class="theme-icon light">☀️</span>
                        <span class="theme-icon dark">🌙</span>
                    </button>
                </li>
                <?php            
                if(isset($_SESSION['user_id'])) {
                    echo '<li class="connexion"><a href="profil.php">' . $_SESSION["Prenom"] . '</a></li>';
                } else {
                    echo '<li class="connexion"><a href="connexion.php">Connexion</a></li>';
                }
                ?>
                <li><a href="panier.php">🛒 Panier</a></li>
            </ul>
        </nav>
    </div>
    <link rel="stylesheet" href="../css/bouton_changement.css">
    <script>
        // Applique immédiatement le thème du cookie pour éviter un flash du mauvais thème
        document.documentElement.setAttribute('data-theme', '<?php echo $theme; ?>');
    </script>
    <script src="../js/bouton_changement.js" defer></script>
</header>
