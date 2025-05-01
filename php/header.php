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
            <img class="logo" src="../photo/logo.png" alt="Logo Stade Trotter">
        </a>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="destinations.php">Destinations</a></li>
                <li><a href="a_propos.php">À propos</a></li>
                <?php
            
                if(isset($_SESSION['user'])) {
                    if($_SESSION['user'] == "31a446ed3e48942499fa6eec61b14eca563dc2d7210ba41d3807407c3e1de0c2"){
                        echo '<li class="connexion"><a href="admin.php">' . "ADMIN" . '</a></li>';
                    } else {
                        echo '<li class="connexion"><a href="profil.php">' . $_SESSION["Prenom"] . '</a></li>';
                    }
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
        // Apply theme from cookie immediately to prevent flash of wrong theme
        document.documentElement.setAttribute('data-theme', '<?php echo $theme; ?>');
    </script>
    <script src="../js/bouton_changement.js" defer></script>
</header>
