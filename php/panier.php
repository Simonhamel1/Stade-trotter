<?php 
    session_start();
    require('getapikey.php');
    $vendeur = 'MEF-2_D';
    $api_key = getAPIKey($vendeur);
    $retour = 'http://localhost/StadeTrotter/php/retour.php';       
    $transaction = uniqid();

    // Chemin vers le fichier JSON des paniers
    $paniers_json_file = '../data/paniers.json';
    
    // Initialiser le panier s'il n'existe pas encore
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    
    // Fonction pour charger tous les paniers depuis le fichier JSON
    function loadPaniers() {
        global $paniers_json_file;
        if (file_exists($paniers_json_file)) {
            $content = file_get_contents($paniers_json_file);
            if (!empty($content)) {
                return json_decode($content, true);
            }
        }
        return [];
    }
    
    // Fonction pour sauvegarder tous les paniers dans le fichier JSON
    function savePaniers($paniers) {
        global $paniers_json_file;
        
        // Créer le dossier data s'il n'existe pas
        $dir = dirname($paniers_json_file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($paniers_json_file, json_encode($paniers, JSON_PRETTY_PRINT));
    }
    
    // Fonction pour sauvegarder le panier actuel de l'utilisateur
    function saveCurrentPanier() {
        global $transaction;
        
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            $paniers = loadPaniers();
            
            // Récupérer l'ID utilisateur s'il est connecté
            $utilisateur_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest_' . session_id();
            
            // Créer une entrée pour ce panier
            $panier_data = [
                'panier_id' => $transaction,
                'date_creation' => date('Y-m-d H:i:s'),
                'utilisateur_id' => $utilisateur_id,
                'voyages' => $_SESSION['panier'],
                'montant_total' => calculateTotalPrice($_SESSION['panier']),
                'statut' => 'en_attente' // en_attente, payé, annulé
            ];
            
            // Ajouter ou mettre à jour le panier
            $paniers[$transaction] = $panier_data;
            
            // Sauvegarder tous les paniers
            savePaniers($paniers);
            
            return $transaction;
        }
        
        return false;
    }
    
    // Calculer le prix total d'un tableau de voyages
    function calculateTotalPrice($voyages) {
        $total = 0;
        foreach ($voyages as $voyage) {
            $total += $voyage['final_price'];
        }
        return $total;
    }
    
    // Ajouter le voyage au panier si les données du voyage sont disponibles
    if (isset($_SESSION['voyage_data'])) {
        $voyage = $_SESSION['voyage_data'];
        
        // Vérifier si le voyage existe déjà dans le panier
        $voyageExiste = false;
        foreach ($_SESSION['panier'] as $key => $panierItem) {
            if ($panierItem['voyage_id'] == $voyage['voyage_id']) {
                // Mettre à jour le voyage existant
                $_SESSION['panier'][$key] = $voyage;
                $voyageExiste = true;
                break;
            }
        }
        
        // Si le voyage n'existe pas dans le panier, l'ajouter
        if (!$voyageExiste) {
            // Ajouter un identifiant unique au panier pour cette entrée
            $voyage['panier_id'] = uniqid();
            $_SESSION['panier'][] = $voyage;
        }
        
        // Supprimer les données temporaires du voyage
        unset($_SESSION['voyage_data']);
        
        // Rediriger pour éviter de rajouter le même voyage en cas de rafraîchissement
        header('Location: panier.php');
        exit;
    }
    
    // Supprimer un voyage du panier si demandé
    if (isset($_GET['supprimer']) && isset($_GET['id'])) {
        foreach ($_SESSION['panier'] as $key => $panierItem) {
            if ($panierItem['panier_id'] == $_GET['id']) {
                unset($_SESSION['panier'][$key]);
                break;
            }
        }
        // Réindexer le tableau après suppression
        $_SESSION['panier'] = array_values($_SESSION['panier']);
        
        // Rediriger pour éviter de supprimer plusieurs fois
        header('Location: panier.php');
        exit;
    }
    
    // Calculer le prix total du panier
    $prixTotalPanier = calculateTotalPrice($_SESSION['panier']);

    $control = md5( $api_key
    . "#" . $transaction
    . "#" . $prixTotalPanier
    . "#" . $vendeur
    . "#" . $retour . "#" );
    
    // Si l'utilisateur procède au paiement, sauvegarder le panier
    if (isset($_POST['save_recap']) && $_POST['save_recap'] == '1') {
        saveCurrentPanier();
        // La redirection vers la page de paiement se fait normalement via le formulaire
    }
?>    
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Réservation de voyage</title>
    <link rel="stylesheet" href="../css/panier.css">
    <script src="../js/navbar.js"></script>
</head>
<body>
    <!-- Entête navbar -->
    <?php include './header.php'; ?>

    <!-- Contenu principal -->
    <main class="panier-container">
        <h1>Mon Panier</h1>
        
        <?php if (empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide.</p>
                <a href="destinations.php" class="btn-continuer">Découvrir nos destinations</a>
            </div>
        <?php else: ?>
            <div class="panier-items">
                <?php foreach ($_SESSION['panier'] as $voyage): ?>
                    <div class="panier-item">
                        <div class="panier-item-header">
                            <h2><?php echo htmlspecialchars($voyage['voyage_name']); ?></h2>
                            <a href="panier.php?supprimer=1&id=<?php echo $voyage['panier_id']; ?>" class="btn-supprimer">Supprimer</a>
                        </div>
                        
                        <div class="panier-item-details">
                            <div class="panier-item-info">
                                <p><strong>Dates :</strong> Du <?php echo date('d/m/Y', strtotime($voyage['date_depart'])); ?> au <?php echo date('d/m/Y', strtotime($voyage['date_retour'])); ?></p>
                                <p><strong>Nombre de participants :</strong> <?php echo $voyage['nb_participants']; ?></p>
                                
                                <?php if (!empty($voyage['steps_details'])): ?>
                                    <div class="etapes-voyage">
                                        <h3>Étapes du voyage</h3>
                                        <div class="timeline">
                                            <?php foreach ($voyage['steps_details'] as $etapeIndex => $etapeDetails): ?>
                                                <div class="timeline-item">
                                                    <div class="timeline-marker">
                                                        <span class="step-number"><?php echo ($etapeIndex + 1); ?></span>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h4>Étape <?php echo ($etapeIndex + 1); ?></h4>
                                                        
                                                        <?php if (isset($etapeDetails['date'])): ?>
                                                            <div class="date-badge">
                                                                <i class="fa fa-calendar"></i> <?php echo $etapeDetails['date']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="etape-options">
                                                            <?php
                                                            // Parcourir les détails pour afficher les options choisies
                                                            foreach ($etapeDetails as $categorie => $valeur) {
                                                                if ($categorie === 'total' || $categorie === 'date') {
                                                                    continue; // Traitement spécial pour ces champs
                                                                }
                                                                
                                                                echo '<div class="option-category">';
                                                                echo '<span class="category-name">' . ucfirst($categorie) . '</span>';
                                                                echo '<div class="option-values">';
                                                                
                                                                if (is_array($valeur)) {
                                                                    if (isset($valeur[0]) && is_array($valeur[0])) {
                                                                        // Cas d'un tableau d'options
                                                                        foreach ($valeur as $option) {
                                                                            if (isset($option['name'])) {
                                                                                echo '<div class="option-item">';
                                                                                echo '<span class="option-name">' . htmlspecialchars($option['name']) . '</span>';
                                                                                if (isset($option['price'])) {
                                                                                    echo '<span class="option-price">' . number_format($option['price'], 2, ',', ' ') . ' €</span>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                        }
                                                                    } else if (isset($valeur['name'])) {
                                                                        // Cas d'une option unique
                                                                        echo '<div class="option-item">';
                                                                        echo '<span class="option-name">' . htmlspecialchars($valeur['name']) . '</span>';
                                                                        if (isset($valeur['price'])) {
                                                                            echo '<span class="option-price">' . number_format($valeur['price'], 2, ',', ' ') . ' €</span>';
                                                                        }
                                                                        echo '</div>';
                                                                    }
                                                                }
                                                                
                                                                echo '</div>'; // Fin option-values
                                                                echo '</div>'; // Fin option-category
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <?php if (isset($etapeDetails['total'])): ?>
                                                            <div class="etape-total">
                                                                Total étape: <span class="price"><?php echo number_format($etapeDetails['total'], 2, ',', ' '); ?> €</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        <div class="panier-item-prix">
                            <p class="prix"><?php echo "Total Voyage: " . number_format($voyage['final_price'], 2, ',', ' '); ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="panier-recapitulatif">
                <div class="panier-total">
                    <h3>Total Panier</h3>
                    <p class="prix-total"><?php echo number_format($prixTotalPanier, 2, ',', ' '); ?> €</p>
                </div>
                
                <div class="panier-actions">
                    <a href="destinations.php" class="btn-continuer">Continuer mes achats</a>
                    <form action='https://www.plateforme-smc.fr/cybank/index.php'
                        method='POST'>
                        <input type='hidden' name='transaction'
                        value='<?php echo $transaction ?>'>
                        <input type='hidden' name='montant' value='<?php echo $prixTotalPanier ?>'>
                        <input type='hidden' name='vendeur' value='<?php echo $vendeur ?>'>
                        <input type='hidden' name='retour'
                        value= '<?php echo $retour ?>' >
                        <input type='hidden' name='control'
                        value='<?php echo $control ?>'>
                        <input type='hidden' name='save_recap' value='1'>
                        <input id="submit" type='submit' value="Procéder au paiement" onclick="savePanierBeforePayment()">
                    </form>                
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- FOOTER -->
    <?php include './footer.php'; ?>

    <script>
        // Script pour confirmer la suppression d'un voyage
        document.addEventListener('DOMContentLoaded', function() {
            const btnSupprimer = document.querySelectorAll('.btn-supprimer');
            
            btnSupprimer.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer ce voyage de votre panier ?')) {
                        e.preventDefault();
                    }
                });
            });
        });

        // Fonction pour sauvegarder le panier avant de rediriger vers la page de paiement
        function savePanierBeforePayment() {
            // Cette fonction est associée au bouton de paiement
            // Le panier sera sauvegardé côté serveur grâce au champ save_recap
            console.log("Sauvegarde du panier avant paiement...");
        }
    </script>
</body>
</html>