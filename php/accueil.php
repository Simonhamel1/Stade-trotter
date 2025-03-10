<!DOCTYPE html>
<html lang="fr">

<!-- Définition de la page -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stade Trotter - Voyages Football</title>

    <link rel="stylesheet" href="../css/acceuil.css">
    <link rel="stylesheet" href="../chatbot/front/chatWidget.css">
    <script src="../js/navbar.js"></script>

    <!-- Importation de la police Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- js -->
    <script src="https://cdn.footystats.org/embeds/standings-loc.js"></script>

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

    <div class="loading-screen">
        <div class="palette"></div>
    </div>

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
        <script> (function (w, d, s, o, f, js, fjs) { w['fsStandingsEmbed'] = o; w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) }; js = d.createElement(s), fjs = d.getElementsByTagName(s)[0]; js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs); }(window, document, 'script', 'mw', 'https://cdn.footystats.org/embeds/standings-loc.js')); mw('params', { leagueID: 2392, lang: 'fr' }); </script>


        <!-- FOOTER -->
        <footer>
            <div class="footer-container">

                <div class="footer-section payment">
                    <h2>Paiements acceptés</h2>
                    <div class="payment-logos">
                        <a href="https://coinmarketcap.com/fr/currencies/bitcoin/" target="_blank"><img
                                src="../photo/btc-logo.png" alt="Bitcoin"></a>
                        <a href="https://www.mastercard.fr/fr-fr.html" target="_blank"><img
                                src="../photo/mastercard-log.png" alt="MasterCard"></a>
                        <a href="https://www.paypal.com/fr/home" target="_blank"><img src="../photo/paypal-logo.png"
                                alt="PayPal"></a>
                        <a href="https://coinmarketcap.com/fr/currencies/solana/" target="_blank"><img
                                src="../photo/sol-logo.jpg" alt="Solana"></a>
                    </div>
                </div>

                <!-- Liens utiles -->
                <div class="footer-section links">
                    <h2>Liens utiles</h2>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Nos services</a></li>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-section contact">
                    <h2>Contact</h2>
                    <p>📍 6 Rue des bleuets, 95000, France</p>
                    <p>📞 +33 6 37 54 61 08</p>
                    <p>📧 stade-trotter@cy-tech.fr</p>
                </div>

                <!-- Réseaux sociaux -->
                <div class="footer-section social">
                    <h2>Suivez-nous</h2>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/" target="_blank"><img src="../photo/logo-facebook.jpg"
                                alt="Facebook"></a>
                        <a href="https://x.com/?mx=2" target="_blank"><img src="../photo/x-logo.png" alt="Twitter"></a>
                        <a href="https://www.instagram.com/" target="_blank"><img src="../photo/insta-logo.jpg"
                                alt="Instagram"></a>
                        <a href="https://www.linkedin.com/in/simon-hamelin" target="_blank"><img
                                src="../photo/linkedin-logo.png" alt="LinkedIn"></a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy;copyrignt 2025 Stade Trotter Simon hamelin et Ewan clabaut</p>
            </div>

        </footer>
    </main>

    <script src="script.js"></script>
</body>

</html>