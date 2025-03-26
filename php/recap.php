<?php
session_start();

$utilisateurId = $_SESSION['utilisateur_id'] ?? '';

// Si le formulaire est soumis, stocker les données en session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_recap'])) {
    $_SESSION['data'] = $_POST['etapes'] ?? [];
    $_SESSION['voyage_id'] = $_POST['voyage_id'] ?? '';
    $_SESSION['date_depart'] = $_POST['date_depart'] ?? '';
    $_SESSION['date_retour'] = $_POST['date_retour'] ?? '';
    $_SESSION['nb_participants'] = $_POST['nb_participants'] ?? '';
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

$finalPrice = $voyage['prix'] ?? 0; // Prix de base
$stepsDetails = []; // Détail et prix par étape

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

$nbParticipants = isset($_SESSION['nb_participants']) ? (int) $_SESSION['nb_participants'] : 1;
$finalPrice *= $nbParticipants;

$recapResults = [
    'voyage_id'       => $voyage_id,
    'voyage_name'     => $voyage['name'],
    'date_depart'     => $_SESSION['date_depart'] ?? '',
    'date_retour'     => $_SESSION['date_retour'] ?? '',
    'nb_participants' => $nbParticipants,
    'final_price'     => $finalPrice,
    'steps_details'   => $stepsDetails,
    'utilisateur_id'  => $utilisateurId
];

// Enregistrer le récapitulatif dans le fichier JSON et rediriger vers la page de paiement
if (isset($_POST['save_recap'])) {
    $jsonRecapFile = __DIR__ . '/../data/dataVoyages.json';
    if (file_exists($jsonRecapFile)) {
        $existingData = json_decode(file_get_contents($jsonRecapFile), true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    } else {
        $existingData = [];
    }
    
    $existingData[] = $recapResults;
    file_put_contents($jsonRecapFile, json_encode($existingData, JSON_PRETTY_PRINT));
    
    header("Location: paiement.php");
    exit;
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
        <div class="user-info">
            <p>Connecté en tant que : <?= htmlspecialchars($utilisateurId) ?></p>
        </div>
    </header>
    <main>
        <h1>Compte rendu complet de votre voyage</h1>
        <section class="voyage-details">
            <h2><?= htmlspecialchars($voyage['name']) ?></h2>
            <p><?= htmlspecialchars($voyage['description']) ?></p>
            <?php if (isset($voyage['continent'])): ?>
                <p><strong>Continent :</strong> <?= htmlspecialchars($voyage['continent']) ?></p>
            <?php endif; ?>
            <?php if (isset($voyage['prix'])): ?>
                <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix']) ?> €</p>
            <?php endif; ?>
            <p><strong>Nombre de participants :</strong> <?= htmlspecialchars($nbParticipants) ?> personnes</p>
            <p><strong>ID utilisateur :</strong> <?= htmlspecialchars($utilisateurId) ?></p>
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
        
        <form method="post">
            <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($voyage_id) ?>">
            <input type="hidden" name="utilisateur_id" value="<?= htmlspecialchars($utilisateurId) ?>">
            <button type="submit" name="save_recap" value="1">Enregistrer le récapitulatif</button>
        </form>
    </main>
    <footer>
        <?php include __DIR__ . '/footer.php'; ?>
    </footer>
</body>
</html>
