<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/profil.css">

</head>

<body>
    <div id="upbar">
        <a href="accueil.html"><h2>StadeTrotter</h2></a>
    </div>
    <div id="profil">
        <div id="form"> <!-- Toute cette page à refaire avec son css pcq dans la logique j'ai quillé ça, mtn va falloir que ça affiche les infos du form -->
            <form action="https://www.cafe-it.fr/cytech/post.php" method="post">
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
                <div id="bouton">
                    <label for="modifier"></label>
                    <input type="button" id="modifier" value="Modifier">
                    <label for="annuler"></label>
                    <input type="button" id="annuler" value="Annuler">
                </div>
            </form>
        </div>
        <div id="presentation">
            <h2>Lolo le chamallow</h2>
            <div id="photo_profil"></div>
        </div>
    </div>
</body>

</html>