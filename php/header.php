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
                // session_start();
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
                <li class="theme-toggle">
                    <button id="theme-toggle-btn" aria-label="Changer de thème">
                        <span class="theme-icon light">☀️</span>
                        <span class="theme-icon dark">🌙</span>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
    <link rel="stylesheet" href="../css/bouton_changement.css">
    <script src="../js/bouton_changement.js" defer></script>
</header>
