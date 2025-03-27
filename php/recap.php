<?php
session_start();

require('getapikey.php');
$vendeur = 'MEF-2_D';
$api_key = getAPIKey($vendeur); 
$retour = 'http://localhost/StadeTrotter/php/finalisation.php';

// Récupération de l'ID utilisateur depuis la session (déjà connecté)
$utilisateurId = $_SESSION['user'] ?? '';

// Stockage des données saisies (hors sauvegarde du récapitulatif)
// On conserve les dates si elles sont déjà renseignées
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_recap'])) {
    $_SESSION['data'] = $_POST['etapes'] ?? [];
    $_SESSION['voyage_id'] = $_POST['voyage_id'] ?? '';
    if (empty($_SESSION['date_depart'])) {
        $_SESSION['date_depart'] = $_POST['date_depart'] ?? '';
    }
    if (empty($_SESSION['date_retour'])) {
        $_SESSION['date_retour'] = $_POST['date_retour'] ?? '';
    }
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

foreach ($data as $index => $etapeData) {
    if (!isset($voyage['etapes'][$index])) {
        continue;
    }
    
    $etapeVoyage = $voyage['etapes'][$index];
    $stepDetail = [];
    $stepTotal = 0;
    
    foreach ($etapeData as $categorie => $selected) {
        // Conserver la date déjà saisie (pour l'étape)
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
                    $stepDetail[$categorie] = ['name' => $selected, 'price' => $option['price']];
                    $stepTotal += $option['price'];
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

$montant = $finalPrice;
$transition = ($montant * 100000) % 100000000;
$transaction = $transition . "AZERT";

$control = md5( $api_key
 . "#" . $transaction
 . "#" . $montant
 . "#" . $vendeur
 . "#" . $retour . "#" );

// Sauvegarde du récapitulatif dans le fichier JSON si l'utilisateur a cliqué sur "Enregistrer le récapitulatif"
if (isset($_POST['save_recap'])) {
    $jsonRecapFile = __DIR__ . '/../data/dataVoyages.json';
    $existingData = [];
    if (file_exists($jsonRecapFile)) {
        $existingData = json_decode(file_get_contents($jsonRecapFile), true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
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
            <?php if (!empty($voyage['continent'])): ?>
                <p><strong>Continent :</strong> <?= htmlspecialchars($voyage['continent']) ?></p>
            <?php endif; ?>
            <?php if (isset($voyage['prix'])): ?>
                <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix']) ?> €</p>
            <?php endif; ?>
            <p><strong>Nombre de participants :</strong> <?= htmlspecialchars($nbParticipants) ?></p>
            <?php if (!empty($_SESSION['date_depart']) && !empty($_SESSION['date_retour'])): ?>
                <p><strong>Période :</strong> Du <?= htmlspecialchars($_SESSION['date_depart']) ?> au <?= htmlspecialchars($_SESSION['date_retour']) ?></p>
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
        <div class="session-details">
            <form action='https://www.plateforme-smc.fr/cybank/index.php'
                method='POST'>
                <input type='hidden' name='transaction'
                value='<?php echo $transaction ?>'>
                <input type='hidden' name='montant' value='<?php echo $montant ?>'>
                <input type='hidden' name='vendeur' value='<?php echo $vendeur ?>'>
                <input type='hidden' name='retour'
                value= '<?php echo $retour ?>' >
                <input type='hidden' name='control'
                value='<?php echo $control ?>'>
                <input type='submit' value="Enregistrer et payer">
            </form>
        </div>
    </main>
    <footer>
        <?php include __DIR__ . '/footer.php'; ?>
    </footer>
</body>
</html>
