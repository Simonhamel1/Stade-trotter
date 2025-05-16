<?php
session_start();

// Récupérer les informations de la transaction
$transaction_id = $_REQUEST['transaction'] ?? '';
$montant = $_REQUEST['montant'] ?? '';
$paiement_status = $_REQUEST['status'] ?? '';
$transaction_date = date('Y-m-d H:i:s');

// Chemin vers le fichier JSON des voyages
$dataVoyages_file = __DIR__ . '/../data/dataVoyages.json';
$paiements_file = __DIR__ . '/../data/paiements.json';

$message = "";
$status = "error";

if ($paiement_status == 'accepted') {
    // Traitement du paiement accepté pour la modification de voyage
    if (isset($_SESSION['user_id']) && isset($_SESSION['temp_voyage_update'])) {
        $utilisateur_id = $_SESSION['user_id'];
        $voyage_update = $_SESSION['temp_voyage_update'];
        
        // Ajouter le paiement à paiements.json
        $paiements_data = [];

        // Récupérer les données supplémentaires de la transaction
        $vendeur = $_REQUEST['vendeur'] ?? '';
        $control = $_REQUEST['control'] ?? '';
        
        // Charger le fichier de paiements existant
        if (file_exists($paiements_file)) {
            $paiements_data = json_decode(file_get_contents($paiements_file), true) ?: [];
        }
        
        // Ajouter les détails de la transaction
        $paiements_data[] = [
            'transaction_id' => $transaction_id,
            'montant' => $montant,
            'utilisateur_id' => $utilisateur_id,
            'date' => $transaction_date,
            'status' => 'paye',
            'vendeur' => $vendeur,
            'control' => $control,
            'type' => 'modification_voyage',
            'voyage_id' => $voyage_update['updated_voyage']['voyage_id']
        ];
        
        // Sauvegarder les paiements
        file_put_contents($paiements_file, json_encode($paiements_data, JSON_PRETTY_PRINT));

        // Mettre à jour le voyage modifié dans dataVoyages.json
        mettreAJourVoyage($voyage_update['voyage_index'], $voyage_update['updated_voyage']);
        
        // Message de succès
        $message = "Votre paiement pour la modification du voyage a été traité avec succès!";
        $status = "success";
        
        // Nettoyer les variables de session liées à la modification
        unset($_SESSION['temp_voyage_update']);
        unset($_SESSION['payment_info']);
        unset($_SESSION['transaction_data']);
        
        // Ajouter un message de confirmation dans la session
        $_SESSION['message'] = "Votre voyage a été modifié avec succès et le supplément a été payé.";
    } else {
        $message = "Vous devez être connecté et avoir des modifications de voyage en attente. Le paiement a n'a pas été bien rçu et il n'a été enregistrée.";
    }
} else {
    // Paiement refusé ou erreur
    $message = "Une erreur est survenue lors du traitement de votre paiement. Votre voyage n'a pas été modifié.";
}

function mettreAJourVoyage($voyage_index, $updated_voyage) {
    global $dataVoyages_file;
    
    // Charger les données existantes
    if (file_exists($dataVoyages_file)) {
        $dataVoyages = json_decode(file_get_contents($dataVoyages_file), true) ?: [];
        
        // Mettre à jour le voyage à l'index spécifié
        if (isset($dataVoyages[$voyage_index])) {
            $dataVoyages[$voyage_index] = $updated_voyage;
            
            // Ajouter un historique de modification
            if (!isset($dataVoyages[$voyage_index]['modifications'])) {
                $dataVoyages[$voyage_index]['modifications'] = [];
            }
            
            $dataVoyages[$voyage_index]['modifications'][] = [
                'date' => date('Y-m-d H:i:s'),
                'montant_supplément' => $_REQUEST['montant'] ?? 0,
                'transaction_id' => $_REQUEST['transaction'] ?? ''
            ];
            
            // Sauvegarder dans le fichier JSON
            file_put_contents($dataVoyages_file, json_encode($dataVoyages, JSON_PRETTY_PRINT));
            return true;
        }
    }
    
    return false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Modification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            max-width: 600px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        .button {
            padding: 15px 30px;
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
    <div class="message <?php echo $status; ?>">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($status == "success" && isset($_SESSION['temp_voyage_update']['updated_voyage']['voyage_id'])): ?>
        <?php 
            $voyage_id = $_SESSION['temp_voyage_update']['updated_voyage']['voyage_id'];
            $date_depart = $_SESSION['temp_voyage_update']['updated_voyage']['date_depart'];
            $date_retour = $_SESSION['temp_voyage_update']['updated_voyage']['date_retour'];
            $redirect_url = "voyage_details.php?id=" . urlencode($voyage_id) . "&date_depart=" . urlencode($date_depart) . "&date_retour=" . urlencode($date_retour);
        ?>
        <a href="<?php echo $redirect_url; ?>" class="button">Voir les détails du voyage modifié</a>
    <?php else: ?>
        <a href="profil.php" class="button">Retour à votre profil</a>
    <?php endif; ?>
</body>
</html>