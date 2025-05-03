<?php
// admin.php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ./connexion.php');
    exit;
}

// Charger les données utilisateurs
$jsonData = file_get_contents('../data/utilisateurs.json');
$users = json_decode($jsonData, true);

$userId = $_SESSION['user_id'];
$isAdmin = false;

// Vérifier si l'utilisateur existe dans le fichier JSON et est VIP
foreach ($users as $user) {
    if ($user['Id'] == $userId) {
        $isAdmin = isset($user['VIP']) && $user['VIP'] === true;
        break;
    }
}

// Rediriger si l'utilisateur n'est pas admin
if (!$isAdmin) {
    header('Location: ./connexion.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin_button.css">
</head>

<body>
    <div id="main">
        <a href="accueil.php">
            <h1>Liste utilisateurs</h1>
        </a>
        <table id="tableau">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>VIP</th>
                    <th>Banni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $jsonData = file_get_contents('../data/utilisateurs.json');
                $users = json_decode($jsonData, true);

                foreach ($users as $user) {
                    // Initialiser les valeurs par défaut si elles n'existent pas
                    $isVip = isset($user['VIP']) ? $user['VIP'] : false;
                    $isBanned = isset($user['banni']) ? $user['banni'] : false;
                    
                    echo "<tr>\n";
                    echo "    <td>" . htmlspecialchars($user['Nom']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($user['Prenom']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($user['Email']) . "</td>\n";
                    
                    // Cellule pour le statut VIP avec un bouton élégant
                    echo "    <td><span class='status-text'>" . ($isVip ? 'OUI' : 'NON') . "</span>" . 
                         "<button class='toggle-status " . ($isVip ? 'status-active' : 'status-inactive') . "' " .
                         "data-user-id='" . htmlspecialchars($user['Id']) . "' " .
                         "data-status-type='vip' " .
                         "data-current-status='" . ($isVip ? 'true' : 'false') . "'>" .
                         ($isVip ? 'Retirer VIP' : 'Passer VIP') . "</button></td>\n";
                    
                    // Cellule pour le statut Banni avec un bouton élégant
                    echo "    <td><span class='status-text'>" . ($isBanned ? 'OUI' : 'NON') . "</span>" . 
                         "<button class='toggle-status " . ($isBanned ? 'status-active' : 'status-inactive') . "' " .
                         "data-user-id='" . htmlspecialchars($user['Id']) . "' " .
                         "data-status-type='banni' " .
                         "data-current-status='" . ($isBanned ? 'true' : 'false') . "'>" .
                         ($isBanned ? 'Débannir' : 'Bannir') . "</button></td>\n";
                    
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- Inclure le fichier JavaScript -->
    <script src="../js/admin.js"></script>
</body>
</html>
