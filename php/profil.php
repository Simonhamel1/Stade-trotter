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
    <script type='text/javascript' src='../js/modification_utilisateurs.js'></script>
</head>
<body>
    <?php include './header.php'; ?>

    <section id="profil">
        <div class="data">
            <h2>Email</h2>
            <div class="modification">
                <input type="text" id="inputbutton1" value="<?php echo htmlspecialchars($_SESSION['Email']); ?>" readonly>
                <div class="modification_button" id="button1">
                    <button class="permettre_modifications" onclick="modification_utilisateurs('button1');" value="modifier"> Modifier </button>
                </div>
            </div>
        </div>
        <div class="data">
            <h2>Prénom</h2>
            <div class="modification">
                <input type="text" id="inputbutton2" value="<?php echo htmlspecialchars($_SESSION['Prenom']); ?>" readonly>                
                <div class="modification_button" id="button2">
                    <button class="permettre_modifications"  onclick="modification_utilisateurs('button2');" value="modifier"> Modifier </button>
                </div>
            </div>
        </div>
        <div class="data">
            <h2>Nom</h2>
            <div class="modification">
                <input type="text" id="inputbutton3" value="<?php echo htmlspecialchars($_SESSION['Nom']); ?>" readonly>
                <div class="modification_button" id="button3">
                    <button class="permettre_modifications"  onclick="modification_utilisateurs('button3');" value="modifier"> Modifier </button>
                </div>
            </div>
        </div>
        <div class="data">  
            <h2>Club</h2>
            <div class="modification">
                <input type="text" id="inputbutton4" value="<?php echo htmlspecialchars($_SESSION['Club']); ?>" readonly>
                <div class="modification_button" id="button4">
                    <button class="permettre_modifications" onclick="modification_utilisateurs('button4');" value="modifier"> Modifier </button>
                </div>
            </div>
        </div>
        <div class="data">
            <h2>VIP</h2>
            <p><?php echo isset($_SESSION['VIP']) && $_SESSION['VIP'] === true ? 'Vous etes un membre VIP' : 'Non'; ?></p>
            <?php if(isset($_SESSION['VIP']) && $_SESSION['VIP'] === true): ?>
            <a href="./admin.php" class="admin-button">Accéder à la page Admin</a>
            <?php endif; ?>
        </div>
        <div class="data" id="deconnexion">
            <a href="./session/deconnexion.php">Deconnexion</a>
        </div>
    </section>

    <?php include '../php/footer.php'; ?>
</body>
</html>
