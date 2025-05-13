<?php 
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('Location:./connexion.php');
    }

    if (isset($_SESSION['user_id'])) {
        echo '<script>console.log("Utilisateur connecté : ' . $_SESSION['user_id'] . '");</script>';
    } 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stade Trotter - Voyages Football</title>
    <link rel="stylesheet" href="../css/acceuil.css">
    <script src="../js/navbar.js"></script>

    <!-- Importation de la police Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <?php if(basename($_SERVER['PHP_SELF']) === 'accueil.php'): ?>
    <!-- Chat Widget -->
    <div id="chat-widget-container">
        <button id="chat-toggle-btn">💬</button>
        <iframe id="chat-iframe" src="../chatbot/chatbot.html"></iframe>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chatIframe = document.getElementById("chat-iframe");
            const chatToggleBtn = document.getElementById("chat-toggle-btn");
        
            chatToggleBtn.addEventListener("click", function () {
                if (chatIframe.style.display === "none" || chatIframe.style.display === "") {
                    chatIframe.style.display = "block";
                    setTimeout(() => chatIframe.style.transform = "translateY(0%)", 10);
                } else {
                    chatIframe.style.transform = "translateY(110%)";
                    setTimeout(() => chatIframe.style.display = "none", 300);
                }
            });
        });
    </script>
    <?php endif; ?>

    <!-- Entête navbar -->
    <?php include './header.php'; ?>

    <!-- Contenu principal -->
    <main>
        <!-- Section Slogan -->
        <section class="photo">
            <div class="image-grid">
                <div class="image-large">
                    <div class="slogan">Osez l'émotion, vivez le rêve avec Stade Trotter!</div>
                </div>
                <div class="image-small"><img src="../photo/acceuil/suporter1-accueil.jpg" alt="Supporters"></div>
                <div class="image-small"><img src="../photo/acceuil/supporter2_acceuil.jpeg" alt="Supporters"></div>
                <div class="image-small"><img src="../photo/acceuil/supporter3-acceuil.jpg" alt="Supporters"></div>
                <div class="image-small"><img src="../photo/acceuil/supporter5.jpg" alt="Supporters"></div>
            </div>
        </section>

        <!-- Présentation de l’agence -->
        <section class="agence-info">
            <h1>Bienvenue chez Stade Trotter</h1>
            <p>
                Stade Trotter, c’est bien plus qu’une agence de voyages. Nous créons des expériences inoubliables en
                transportant les passionnés de football vers des destinations uniques autour du globe. Que vous soyez un
                fervent supporter ou un amoureux du sport, vivez des moments d’exception en découvrant la magie du beau
                jeu et la richesse culturelle de chaque lieu visité.
            </p>
        </section>

        <!-- Témoignages -->
        <section class="temoignages">
            <article class="temoignage-groupe">
                <div class="temoignage">
                    <img src="../photo/acceuil/supporter8.jpg" alt="Témoignage">
                </div>
                <div class="temoignage-texte">
                    <h1>Alban, René et Albert</h1>
                    <p>« Une expérience incroyable, une organisation sans faille et des moments gravés à jamais. Merci
                        Stade Trotter pour cette aventure mémorable ! »</p>
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                </div>
            </article>

            <article class="temoignage-groupe">
                <div class="temoignage">
                    <img src="../photo/acceuil/supproter4.acceuil.jpg" alt="Témoignage">
                </div>
                <div class="temoignage-texte">
                    <h1>Joséphine et Sandrine</h1>
                    <p>« Un voyage exceptionnel, une organisation parfaite et des souvenirs inoubliables. Merci Stade
                        Trotter pour cette expérience unique ! »</p>
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                </div>
            </article>
        </section>

        <div id="fs-standings"></div>
        <script>
            (function (w, d, s, o, f, js, fjs) {
                w['fsStandingsEmbed'] = o;
                w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) };
                js = d.createElement(s),
                fjs = d.getElementsByTagName(s)[0];
                js.id = o;
                js.src = f;
                js.async = 1;
                fjs.parentNode.insertBefore(js, fjs);
            }(window, document, 'script', 'mw', 'https://cdn.footystats.org/embeds/standings-loc.js'));
            mw('params', { leagueID: 2392, lang: 'fr' });
        </script>

        <!-- FOOTER -->
        <?php include './footer.php'; ?>

    </main>
</body>
</html>
