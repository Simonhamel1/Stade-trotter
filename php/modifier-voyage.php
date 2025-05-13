<?php
    session_start();
    
    // Inclure le fichier pour obtenir l'API key
    require('getapikey.php');
    $vendeur = 'MEF-2_D';
    $api_key = getAPIKey($vendeur);
    $retour = 'http://localhost/StadeTrotter/php/retour2.php';
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['Email'])) {
        header('Location: ./login.php');
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
        
        // Rechercher le voyage spécifique
        foreach ($voyages_data as $key => $v) {
            if ($v['voyage_id'] === $voyage_id && $v['utilisateur_id'] === $_SESSION['user_id'] && 
                $v['date_depart'] === $date_depart && $v['date_retour'] === $date_retour) {
                $voyage = $v;
                $voyage_index = $key; // Sauvegarder l'index pour la mise à jour ultérieure
                break;
            }
        }
    }
    
    // Rediriger si le voyage n'existe pas ou n'appartient pas à l'utilisateur
    if (!$voyage) {
        header('Location: ./profil.php');
        exit;
    }

    // Charger les données des destinations disponibles
    $destinations_json_file = '../data/voyages.json';
    $destination = null;
    
    if (file_exists($destinations_json_file)) {
        $destinations = json_decode(file_get_contents($destinations_json_file), true);
        if (isset($destinations[$voyage_id])) {
            $destination = $destinations[$voyage_id];
        } else {
            header('Location: ./profil.php');
            exit;
        }
    } else {
        die("Le fichier de destinations n'existe pas.");
    }
    
    // Traitement du formulaire si soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les nouvelles informations
        $new_date_depart = $_POST['date_depart'];
        $new_date_retour = $_POST['date_retour'];
        $new_nb_participants = intval($_POST['nb_participants']);
        
        // Calculer l'ancien prix total
        $old_price = $voyage['final_price'];
        
        // Initialiser les détails des étapes
        $steps_details = [];
        $new_total_price = 0;
        
        // Traiter chaque étape
        foreach ($_POST['etapes'] as $index => $etape_data) {
            $step_total = 0;
            $step_details = [];
            
            // Traitement de l'hébergement
            if (isset($etape_data['hebergement'])) {
                foreach ($destination['etapes'][$index]['options']['hebergement'] as $option) {
                    if ($option['name'] === $etape_data['hebergement']) {
                        $step_details['hebergement'] = [
                            'name' => $option['name'],
                            'price' => $option['price'] * $new_nb_participants
                        ];
                        $step_total += $step_details['hebergement']['price'];
                        break;
                    }
                }
            }
            
            // Traitement de la restauration
            if (isset($etape_data['restauration'])) {
                foreach ($destination['etapes'][$index]['options']['restauration'] as $option) {
                    if ($option['name'] === $etape_data['restauration']) {
                        $step_details['restauration'] = [
                            'name' => $option['name'],
                            'price' => $option['price'] * $new_nb_participants
                        ];
                        $step_total += $step_details['restauration']['price'];
                        break;
                    }
                }
            }
            
            // Traitement du transport
            if (isset($etape_data['transport'])) {
                foreach ($destination['etapes'][$index]['options']['transport'] as $option) {
                    if ($option['name'] === $etape_data['transport']) {
                        $step_details['transport'] = [
                            'name' => $option['name'],
                            'price' => $option['price'] * $new_nb_participants
                        ];
                        $step_total += $step_details['transport']['price'];
                        break;
                    }
                }
            }
            
            // Traitement des activités (cases à cocher)
            if (isset($etape_data['activites']) && is_array($etape_data['activites'])) {
                $step_details['activites'] = [];
                
                foreach ($etape_data['activites'] as $selected_activity) {
                    foreach ($destination['etapes'][$index]['options']['activites'] as $option) {
                        if ($option['name'] === $selected_activity) {
                            $activity_detail = [
                                'name' => $option['name'],
                                'price' => $option['price'] * $new_nb_participants
                            ];
                            $step_details['activites'][] = $activity_detail;
                            $step_total += $activity_detail['price'];
                            break;
                        }
                    }
                }
            }
            
            // Traitement de la garde d'enfants si disponible
            if (isset($etape_data['garde_enfants'])) {
                foreach ($destination['etapes'][$index]['options']['garde_enfants'] as $option) {
                    if ($option['name'] === $etape_data['garde_enfants']) {
                        $step_details['garde_enfants'] = [
                            'name' => $option['name'],
                            'price' => $option['price'] * $new_nb_participants
                        ];
                        $step_total += $step_details['garde_enfants']['price'];
                        break;
                    }
                }
            }
            
            // Ajouter le total de l'étape
            $step_details['total'] = $step_total;
            $steps_details[] = $step_details;
            $new_total_price += $step_total;
        }
        
        // Mettre à jour les données du voyage
        $voyages_data[$voyage_index]['date_depart'] = $new_date_depart;
        $voyages_data[$voyage_index]['date_retour'] = $new_date_retour;
        $voyages_data[$voyage_index]['nb_participants'] = $new_nb_participants;
        $voyages_data[$voyage_index]['steps_details'] = $steps_details;
        $voyages_data[$voyage_index]['final_price'] = $new_total_price;
        
        // Calculer la différence de prix
        $price_difference = $new_total_price - $old_price;
        
        // Sauvegarder temporairement les modifications dans la session
        $_SESSION['temp_voyage_update'] = [
            'voyage_index' => $voyage_index,
            'updated_voyage' => $voyages_data[$voyage_index],
            'price_difference' => $price_difference
        ];
        
        // Rediriger vers la page de confirmation/paiement si nécessaire
        if ($price_difference > 0) {
            // Stocker l'information sur le paiement supplémentaire dans la session
            $_SESSION['payment_info'] = [
                'amount' => $price_difference,
                'description' => 'Supplément modification voyage ' . $voyage_id,
                'voyage_id' => $voyage_id
            ];
            
            // Générer un ID de transaction unique
            $transaction = uniqid();
            
            // Générer un hash de contrôle pour la sécurité de la transaction
            $control = md5($api_key . "#" . $transaction . "#" . $price_difference . "#" . $vendeur . "#" . $retour . "#");
            
            // Sauvegarder les informations de transaction dans la session
            $_SESSION['transaction_data'] = [
                'transaction' => $transaction,
                'control' => $control
            ];
            
            // Rediriger directement vers la plateforme de paiement
            header('Location: https://www.plateforme-smc.fr/cybank/index.php?vendeur=' . urlencode($vendeur) . 
                   '&transaction=' . urlencode($transaction) . 
                   '&montant=' . urlencode($price_difference) . 
                   '&retour=' . urlencode($retour) . 
                   '&control=' . urlencode($control));
            exit;
        } else {
            // Sauvegarder directement les modifications si pas de supplément à payer
            file_put_contents($voyages_json_file, json_encode($voyages_data, JSON_PRETTY_PRINT));
            $_SESSION['message'] = 'Votre voyage a été modifié avec succès!';
            header('Location: ./profil.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le voyage - <?php echo htmlspecialchars($voyage['voyage_name']); ?></title>
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="../css/voyages.css">
    <script src="../js/prix_dynamique.js" defer></script>
</head>
<body>
    <?php include './header.php'; ?>

    <main>
        <div class="content-container">
            <section class="voyage-header">
                <h1>Modifier votre voyage : <?= htmlspecialchars($voyage['voyage_name']); ?></h1>
                <img src="../photo/<?= htmlspecialchars($destination['image']); ?>" alt="<?= htmlspecialchars($voyage['voyage_name']); ?>">
                <p><?= htmlspecialchars($destination['description']); ?></p>
                
                <div class="voyage-details">
                    <?php if (isset($destination['continent'])): ?>
                        <p><strong>Continent :</strong> <?= htmlspecialchars($destination['continent']); ?></p>
                    <?php endif; ?>
                    
                    <div class="prix-container">
                        <p id="prix-base" data-prix="<?= htmlspecialchars($destination['prix']); ?>">
                            <strong>Prix de base par personne :</strong> <?= htmlspecialchars($destination['prix']); ?> €
                        </p>
                        <p>
                            <strong>Prix actuel :</strong> <?= number_format($voyage['final_price'], 2, ',', ' '); ?> €
                        </p>
                        <p>
                            <strong>Nouveau prix total estimé :</strong> 
                            <span class="prix-total"><span id="prix-total">0.00</span> €</span>
                        </p>
                    </div>
                </div>
            </section>
            
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . urlencode($voyage_id) . '&date_depart=' . urlencode($date_depart) . '&date_retour=' . urlencode($date_retour); ?>" method="post">
                <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($voyage_id); ?>">
                <input type="hidden" name="total_price" value="0">

                <!-- Section des dates -->
                <section class="date-selection">
                    <h2>Dates du voyage</h2>
                    <div class="date-input">
                        <div>
                            <label for="date_depart">Date de départ :</label>
                            <input type="date" id="date_depart" name="date_depart" value="<?= htmlspecialchars($voyage['date_depart']); ?>" required>
                        </div>
                        
                        <div>
                            <label for="date_retour">Date de retour :</label>
                            <input type="date" id="date_retour" name="date_retour" value="<?= htmlspecialchars($voyage['date_retour']); ?>" required>
                        </div>
                    </div>
                </section>
                
                <!-- Section des participants -->
                <section class="participants">
                    <div class="participant-count">
                        <label for="nb_participants">Nombre de participants :</label>
                        <input type="number" id="nb_participants" name="nb_participants" min="1" max="8" value="<?= htmlspecialchars($voyage['nb_participants']); ?>" required>
                    </div>
                </section>

                <?php if (isset($destination['etapes']) && is_array($destination['etapes'])): ?>
                    <?php foreach ($destination['etapes'] as $index => $etape): ?>
                    <section class="etape">
                        <h2>Étape <?= $index + 1 ?> : <?= htmlspecialchars($etape['titre']); ?></h2>
                        <p><strong>Lieu :</strong> <?= htmlspecialchars($etape['lieu']); ?></p>
                        <p><?= htmlspecialchars($etape['description']); ?></p>
                        
                        <!-- Options pour cette étape -->
                        <?php foreach ($etape['options'] as $categorie => $options): ?>
                            <div class="option-group">
                                <label for="etape<?= $index ?>_<?= $categorie ?>"><?= ucfirst($categorie); ?> :</label>
                                <?php if ($categorie === 'activites'): ?>
                                    <fieldset>
                                        <legend>Choisissez vos activités :</legend>
                                        <?php 
                                        $selected_activites = [];
                                        if (isset($voyage['steps_details'][$index]['activites'])) {
                                            foreach ($voyage['steps_details'][$index]['activites'] as $act) {
                                                $selected_activites[] = $act['name'];
                                            }
                                        }
                                        ?>
                                        <?php foreach ($options as $option): ?>
                                            <label>
                                                <input type="checkbox" name="etapes[<?= $index ?>][<?= $categorie ?>][]" value="<?= htmlspecialchars($option['name']); ?>"
                                                    <?= in_array($option['name'], $selected_activites) ? 'checked' : ''; ?>>
                                                <?= htmlspecialchars($option['name']); ?> (<?= htmlspecialchars($option['price']); ?> €)
                                            </label>
                                        <?php endforeach; ?>
                                    </fieldset>
                                <?php else: ?>
                                    <select name="etapes[<?= $index ?>][<?= $categorie ?>]" id="etape<?= $index ?>_<?= $categorie ?>">
                                        <?php 
                                        $selected_option = '';
                                        if (isset($voyage['steps_details'][$index][$categorie])) {
                                            $selected_option = $voyage['steps_details'][$index][$categorie]['name'];
                                        }
                                        ?>
                                        <?php foreach ($options as $option): ?>
                                            <option value="<?= htmlspecialchars($option['name']); ?>" 
                                                <?= ($selected_option === $option['name']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($option['name']); ?> (<?= htmlspecialchars($option['price']); ?> €)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="submit-container">
                    <a href="javascript:history.back()" class="btn-back">Annuler</a>
                    <button type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </main>
    
    <?php include './footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour calculer le prix total
            function calculateTotalPrice() {
                let total = 0;
                
                // Récupérer le nombre de participants
                const nbParticipants = parseInt(document.getElementById('nb_participants').value) || 1;
                
                // Pour chaque étape, ajouter les prix sélectionnés
                document.querySelectorAll('.etape').forEach(function(etape, etapeIndex) {
                    // Calculer pour les sélecteurs (hébergement, restauration, transport, garde d'enfants)
                    etape.querySelectorAll('select').forEach(function(select) {
                        const selectedOption = select.options[select.selectedIndex];
                        const priceText = selectedOption.textContent.match(/\(([^)]+)\)/)[1];
                        const price = parseFloat(priceText.replace('€', '').trim().replace(',', '.'));
                        if (!isNaN(price)) {
                            total += price * nbParticipants;
                        }
                    });
                    
                    // Calculer pour les activités (checkboxes)
                    etape.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
                        const priceText = checkbox.parentElement.textContent.match(/\(([^)]+)\)/)[1];
                        const price = parseFloat(priceText.replace('€', '').trim().replace(',', '.'));
                        if (!isNaN(price)) {
                            total += price * nbParticipants;
                        }
                    });
                });
                
                // Mettre à jour l'affichage du prix total
                document.getElementById('prix-total').textContent = total.toFixed(2).replace('.', ',');
                document.querySelector('input[name="total_price"]').value = total.toFixed(2);
            }
            
            // Calculer le prix initial
            calculateTotalPrice();
            
            // Recalculer le prix lorsque les options changent
            document.querySelectorAll('select, input[type="checkbox"], #nb_participants').forEach(function(element) {
                element.addEventListener('change', calculateTotalPrice);
            });
            
            // Définir la date minimum (aujourd'hui) pour les champs de date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_depart').min = today;
            document.getElementById('date_retour').min = today;
            
            // Validation: la date de retour doit être après la date de départ
            document.getElementById('date_depart').addEventListener('change', function() {
                document.getElementById('date_retour').min = this.value;
                
                // Si la date de retour est avant la nouvelle date de départ, on l'ajuste
                if (document.getElementById('date_retour').value < this.value) {
                    document.getElementById('date_retour').value = this.value;
                }
            });
        });
    </script>
</body>
</html>