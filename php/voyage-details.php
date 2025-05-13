<?php
    session_start();
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['Email'])) {
        header('Location: ./profil.php');
        exit;
    }
    
    // Vérifier si un ID de voyage est fourni
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: ./profil.php');
        exit;
    }
    
    $voyage_id = $_GET['id'];
    $date_depart = $_GET['date_depart'] ?? '';
    $date_retour = $_GET['date_retour'] ?? '';

    
    // Vérifier si l'ID de l'utilisateur est défini dans la session
    if (!isset($_SESSION['user_id'])) {
        header('Location: ./profil.php');
        exit;
    }
    
    // Charger les données des voyages depuis le fichier JSON
    $voyages_json_file = '../data/dataVoyages.json';
    $voyage = null;
    
    if (file_exists($voyages_json_file)) {
        $voyages_data = json_decode(file_get_contents($voyages_json_file), true);
        
        // Initialiser la variable v
        $v = null;
        // Rechercher le voyage spécifique
        foreach ($voyages_data as $v) {
            if ($v['voyage_id'] === $voyage_id && $v['utilisateur_id'] === $_SESSION['user_id'] && $v['date_depart'] === $date_depart && $v['date_retour'] === $date_retour ) {
                $voyage = $v;
                break;
            }
        }
    }
    
    // Rediriger si le voyage n'existe pas ou n'appartient pas à l'utilisateur
    if (!$voyage) {
        header('Location: ./profil.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du voyage - <?php echo htmlspecialchars($voyage['voyage_name']); ?></title>
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="../css/voyage-details.css">
</head>
<body>
    <?php include './header.php'; ?>

    <section id="voyage-details">
        <div class="voyage-header">
            <h1><?php echo htmlspecialchars($voyage['voyage_name']); ?></h1>
            <div class="voyage-meta">
                <p><span>Dates du séjour :</span> <?php echo date('d/m/Y', strtotime($voyage['date_depart'])); ?> au <?php echo date('d/m/Y', strtotime($voyage['date_retour'])); ?></p>
                <p><span>Nombre de participants :</span> <?php echo $voyage['nb_participants']; ?></p>
                <p><span>Prix total :</span> <?php echo number_format($voyage['final_price'], 2, ',', ' '); ?> €</p>
            </div>
        </div>

        <div class="steps-container">
            <h2>Détails des étapes du voyage</h2>

            <?php foreach ($voyage['steps_details'] as $index => $step): ?>
                <div class="step-card">
                    <h3>Étape <?php echo $index + 1; ?></h3>
                    
                    <div class="step-details">
                        <?php if (isset($step['hebergement'])): ?>
                            <div class="detail-item">
                                <h4>Hébergement</h4>
                                <p><?php echo htmlspecialchars($step['hebergement']['name']); ?></p>
                                <p class="price"><?php echo number_format($step['hebergement']['price'], 2, ',', ' '); ?> €</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($step['restauration'])): ?>
                            <div class="detail-item">
                                <h4>Restauration</h4>
                                <p><?php echo htmlspecialchars($step['restauration']['name']); ?></p>
                                <p class="price"><?php echo number_format($step['restauration']['price'], 2, ',', ' '); ?> €</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($step['transport'])): ?>
                            <div class="detail-item">
                                <h4>Transport</h4>
                                <p><?php echo htmlspecialchars($step['transport']['name']); ?></p>
                                <p class="price"><?php echo number_format($step['transport']['price'], 2, ',', ' '); ?> €</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($step['activites']) && !empty($step['activites'])): ?>
                            <div class="detail-item">
                                <h4>Activités</h4>
                                <ul>
                                    <?php foreach ($step['activites'] as $activite): ?>
                                        <li>
                                            <?php echo htmlspecialchars($activite['name']); ?>
                                            <span class="price"><?php echo number_format($activite['price'], 2, ',', ' '); ?> €</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($step['garde_enfants'])): ?>
                            <div class="detail-item">
                                <h4>Service de garde d'enfants</h4>
                                <p><?php echo htmlspecialchars($step['garde_enfants']['name']); ?></p>
                                <p class="price"><?php echo number_format($step['garde_enfants']['price'], 2, ',', ' '); ?> €</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="step-total">
                        <p>Total de l'étape : <span><?php echo number_format($step['total'], 2, ',', ' '); ?> €</span></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="voyage-actions">
            <a href="./profil.php" class="btn-back">Retour à mon profil</a>
            <a href="./modifier-voyage.php?id=<?php echo $voyage_id; ?>&date_depart=<?php echo urlencode($date_depart); ?>&date_retour=<?php echo urlencode($date_retour); ?>&price=<?php echo urlencode($voyage['final_price']); ?>" class="btn-edit">Modifier le voyage</a>
            <a href="./destinations.php" class="btn-recap">Voir un autre voyage</a>
        </div>
    </section>

    <?php include './footer.php'; ?>
</body>
</html>