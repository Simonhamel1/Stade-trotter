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
    <header>
        <div class="container">
          <a href="#top">
            <img class="logo" src="../photo/logo.png" alt="Logo Stade Trotter">
          </a>
          <nav>
            <ul>
              <li><a href="accueil.html">Accueil</a></li>
              <li><a href="destinations.html">Destinations</a></li>
              <li><a href="a_propos.html">À propos</a></li>
              <li><a href="profil.html">Profil</a></li>
              <li class="btn"><a href="connexion.html">Connexion</a></li>
            </ul>
          </nav>
        </div>
      </header>
    
    <main>
      <section class="form-container">
        <img src="../photo/logo.png" alt="Football Club Logo" id="form_logo" >
        <h2>⚽ Inscription ⚽</h2>
        <form action="../php/post.php" method="post">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="Nom" required>

            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="Prénom" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="Email" required>

            <label for="sexe">Sexe:</label>
            <select name="Sexe" id="sexe">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="Password" required>

            <label for="password">Confirmer le mot de passe:</label>
            <input type="password" id="password" name="Password" required>


            <label for="club">Club préféré:</label>
            <select id="club" name="Club">
                <option value="PSG">PSG</option>
                <option value="Real Madrid">Real Madrid</option>
                <option value="Barcelone">FC Barcelone</option>
                <option value="Bayern">Bayern Munich</option>
                <option value="Autre">Autre</option>
            </select>

            <input type="submit" value="S'inscrire">
            <p>Déjà un compte ? <a href="connexion.html">Se connecter</a></p>
        </form>
    </section>
  </main>
</body>
</html>
