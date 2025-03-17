<?php include './session/session.php';?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/profil.css">

</head>

<body>
<?php include './header.php'; ?>
    <div id="profil">
        <div id="Email" class="data">
            <?php
            echo $_SESSION['Email'];
            ?>
        </div>
        <div id="Prenom" class="data">
        <?php
            echo $_SESSION['Prenom'];
            ?>
        </div>
        <div id="Nom" class="data">
        <?php
            echo $_SESSION['Nom'];
            ?>
        </div>
        <div id="Club" class="data">
        <?php
            echo $_SESSION['Club'];
            ?>
        </div>
    </div>
</body>
<?php include '../php/footer.php'; ?>
</html>