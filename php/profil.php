<?php 
    session_start();
?>
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

    <section id="profil">
        <div class="data">
            <h2>Email</h2>
            <p><?php echo htmlspecialchars($_SESSION['Email']); ?></p>
        </div>
        <div class="data">
            <h2>Prénom</h2>
            <p><?php echo htmlspecialchars($_SESSION['Prenom']); ?></p>
        </div>
        <div class="data">
            <h2>Nom</h2>
            <p><?php echo htmlspecialchars($_SESSION['Nom']); ?></p>
        </div>
        <div class="data">
            <h2>Club</h2>
            <p><?php echo htmlspecialchars($_SESSION['Club']); ?></p>
        </div>
    </section>

    <?php include '../php/footer.php'; ?>
</body>
</html>
