<?php 
    session_start();
    require('getapikey.php');
    $vendeur = 'MEF-2_D';
    $api_key = getAPIKey($vendeur);
    $retour = 'http://localhost/StadeTrotter/php/retour.php';       
    $transaction = uniqid();


    
    // Initialiser le panier s'il n'existe pas encore
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
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
    $prixTotalPanier = 0;
    foreach ($_SESSION['panier'] as $voyage) {
        $prixTotalPanier += $voyage['final_price'];
    }

    $control = md5( $api_key
    . "#" . $transaction
    . "#" . $prixTotalPanier
    . "#" . $vendeur
    . "#" . $retour . "#" );
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
                                        <ul>
                                            <?php foreach ($voyage['steps_details'] as $etape): ?>
                                                <li>
                                                    <span class="etape-nom"><?php echo htmlspecialchars($etape['nom']); ?></span>
                                                    <?php if (isset($etape['duree'])): ?>
                                                        <span class="etape-duree"><?php echo $etape['duree']; ?> jours</span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="panier-item-prix">
                                <p class="prix"><?php echo number_format($voyage['final_price'], 2, ',', ' '); ?> €</p>
                                <a href="modifier_voyage.php?id=<?php echo $voyage['voyage_id']; ?>" class="btn-modifier">Modifier</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="panier-recapitulatif">
                <div class="panier-total">
                    <h3>Total</h3>
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
                        <input id="submit" type='submit' value="Procéder au paiement">

                        <input type='hidden' name='save_recap' value='1'>
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
    </script>
</body>
</html>