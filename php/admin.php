<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div id="main">
        <a href="accueil.html">
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
                $jsonData = file_get_contents('../donnees/DB.json');
                $users = json_decode($jsonData, true);

                foreach ($users as $user) {
                    echo "<tr>\n";
                    echo "    <td>" . htmlspecialchars($user['Nom']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($user['Prenom']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($user['Email']) . "</td>\n";
                    echo "    <td>" . ($user['VIP'] ? 'OUI' : 'NON') . "</td>\n";
                    echo "    <td>" . ($user['banni'] ? 'OUI' : 'NON') . "</td>\n";
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>