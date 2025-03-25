<?php
session_start();

// Si le formulaire est soumis, stocker les données en session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['data'] = $_POST['etapes'] ?? [];
    $_SESSION['voyage_id'] = $_POST['voyage_id'] ?? '';
}

$data = $_SESSION['data'] ?? [];
$voyage_id = $_SESSION['voyage_id'] ?? '';

if (empty($voyage_id)) {
    echo "<p>ID de voyage manquant. <a href='destinations.php'>Retour aux destinations</a></p>";
    exit;
}

// Chargement des voyages depuis le fichier JSON
$jsonFile = __DIR__ . '/../data/voyages.json';
if (!file_exists($jsonFile)) {
    die("Le fichier de données voyages n'existe pas.");
}

$jsonData = file_get_contents($jsonFile);
$voyages = json_decode($jsonData, true);
if (!$voyages) {
    die("Erreur lors du décodage du fichier JSON.");
}

if (!isset($voyages[$voyage_id])) {
    echo "<p>Voyage non trouvé. <a href='destinations.php'>Retour aux destinations</a></p>";
    exit;
}

$voyage = $voyages[$voyage_id];

$finalPrice = 0;
$stepsDetails = []; // Détail et prix par étape

// Parcours de chaque étape soumise
foreach ($data as $index => $etapeData) {
    if (!isset($voyage['etapes'][$index])) {
        continue;
    }
    
    $etapeVoyage = $voyage['etapes'][$index];
    $stepDetail = [];
    $stepTotal = 0;
    
    foreach ($etapeData as $categorie => $selected) {
        if ($categorie === 'date') {
            $stepDetail[$categorie] = $selected;
            continue;
        }
        if (!isset($etapeVoyage['options'][$categorie])) {
            continue;
        }
        $options = $etapeVoyage['options'][$categorie];
        
        // Traitement des options multiples (ex : activités)
        if (is_array($selected)) {
            $selectedDetails = [];
            foreach ($selected as $sel) {
                foreach ($options as $option) {
                    if ($option['name'] === $sel) {
                        $selectedDetails[] = ['name' => $sel, 'price' => $option['price']];
                        $stepTotal += $option['price'];
                        break;
                    }
                }
            }
            $stepDetail[$categorie] = $selectedDetails;
        } else {
            // Option unique
            foreach ($options as $option) {
                if ($option['name'] === $selected) {
                    $selectedDetail = ['name' => $selected, 'price' => $option['price']];
                    $stepTotal += $option['price'];
                    $stepDetail[$categorie] = $selectedDetail;
                    break;
                }
            }
        }
    }
    
    $stepDetail['total'] = $stepTotal;
    $finalPrice += $stepTotal;
    $stepsDetails[$index] = $stepDetail;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voyage['name']) ?> - Récapitulatif complet</title>
    <link rel="stylesheet" href="../css/recap.css">
</head>
<body>
    <header>
        <?php include __DIR__ . '/header.php'; ?>
    </header>
    <main>
        <h1>Compte rendu complet de votre voyage</h1>
        <section class="voyage-details">
            <h2><?= htmlspecialchars($voyage['name']) ?></h2>
            <p><?= htmlspecialchars($voyage['description']) ?></p>
            <?php if (isset($voyage['continent'])): ?>
                <p><strong>Continent :</strong> <?= htmlspecialchars($voyage['continent']) ?></p>
            <?php endif; ?>
        </section>
        
        <?php if (!empty($stepsDetails)): ?>
            <?php foreach ($stepsDetails as $index => $detail): ?>
                <section class="etape">
                    <h2>Étape <?= $index + 1 ?></h2>
                    <?php if (isset($detail['date'])): ?>
                        <p><strong>Date :</strong> <?= htmlspecialchars($detail['date']) ?></p>
                    <?php endif; ?>
                    
                    <?php foreach ($detail as $categorie => $optionDetail):
                        if ($categorie === 'total' || $categorie === 'date') continue;
                    ?>
                        <div class="detail-option">
                            <p><strong><?= ucfirst($categorie) ?> :</strong>
                            <?php
                            if (is_array($optionDetail) && isset($optionDetail[0]['name'])) {
                                $items = [];
                                foreach ($optionDetail as $item) {
                                    $items[] = htmlspecialchars($item['name']) . " (" . htmlspecialchars($item['price']) . " €)";
                                }
                                echo implode(', ', $items);
                            } elseif (is_array($optionDetail)) {
                                echo htmlspecialchars($optionDetail['name']) . " (" . htmlspecialchars($optionDetail['price']) . " €)";
                            }
                            ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                    <p><strong>Total étape :</strong> <?= $detail['total'] ?> €</p>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune donnée relative aux étapes n'est disponible pour le moment.</p>
        <?php endif; ?>

        <section class="final-price">
            <h2>Prix final de votre voyage : <?= $finalPrice ?> €</h2>
        </section>

        <!-- Affichage optionnel des détails de session pour vérification -->
        <section class="session-details">
            <h2>Détails complets de la session</h2>
            <pre><?php print_r($_SESSION); ?></pre>
        </section>
    </main>
    <footer>
        <?php include __DIR__ . '/footer.php'; ?>
    </footer>
</body>
</html>
