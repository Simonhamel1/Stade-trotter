<?php
session_start();

// Si le formulaire de voyage est soumis, stocke les données dans la session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etapes'])) {
    $_SESSION['data'] = $_POST['etapes'];
}

// Exemple de données pour le récapitulatif
$user = isset($_SESSION['user']) ? $_SESSION['user'] : 'Visiteur';
$data = isset($_SESSION['data']) ? $_SESSION['data'] : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif - Stade Trotter</title>
    <link rel="stylesheet" href="recap.css">
</head>
<body>
    <header>
        <h1>Récapitulatif de votre session</h1>
        <nav>
            <a href="voyage.php">Retour à Voyage</a>
        </nav>
    </header>
    <main>
        <p>Bonjour <?php echo htmlspecialchars($user); ?>, voici votre récapitulatif :</p>
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $index => $etape): ?>
                <section class="etape">
                    <h2>Étape <?php echo $index + 1; ?></h2>
                    <ul>
                        <?php foreach ($etape as $categorie => $value): ?>
                            <li>
                                <?php
                                // Gestion des cases à cocher renvoyant un tableau
                                if (is_array($value)) {
                                    echo ucfirst($categorie) . ' : ' . implode(', ', array_map('htmlspecialchars', $value));
                                } else {
                                    echo ucfirst($categorie) . ' : ' . htmlspecialchars($value);
                                }
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune donnée disponible pour le moment.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Stade Trotter. Tous droits réservés.</p>
    </footer>
</body>
</html>