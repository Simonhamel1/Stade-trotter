<?php
session_start();

// Récupérer les informations de la transaction
$transaction_id = $_REQUEST['transaction'] ?? '';
$montant = $_REQUEST['montant'] ?? '';
$paiement_status = $_REQUEST['status'] ?? '';
$transaction_date = date('Y-m-d H:i:s');

// Chemin vers les fichiers JSON
$paniers_file = __DIR__ . '/../data/paniers.json';
$dataVoyages_file = __DIR__ . '/../data/dataVoyages.json';

$message = "";
$status = "error";

if ($paiement_status == 'accepted') {
    // Traitement du paiement accepté
    if (isset($_SESSION['user_id'])) {
        $utilisateur_id = $_SESSION['user_id'];
        
        // Récupérer le panier de l'utilisateur
        $panier = $_SESSION['panier'] ?? [];
        
        if (!empty($panier)) {
            // Ajouter les voyages achetés à dataVoyages.json
            ajouterVoyagesAchetesAData($panier, $utilisateur_id, $transaction_id, $transaction_date);
            
            // Vider le panier de l'utilisateur dans la session
            $_SESSION['panier'] = [];
            
            // Vider le panier de l'utilisateur dans le fichier JSON
            if (file_exists($paniers_file)) {
                $paniers_data = json_decode(file_get_contents($paniers_file), true) ?: [];
                if (isset($paniers_data[$utilisateur_id])) {
                    $paniers_data[$utilisateur_id] = [];
                    file_put_contents($paniers_file, json_encode($paniers_data, JSON_PRETTY_PRINT));
                }
            }
            
            // Message de succès
            $message = "Votre paiement a été traité avec succès. Merci pour votre achat !";
            $status = "success";
        } else {
            $message = "Votre panier est vide. Le paiement a été accepté mais aucun voyage n'a été traité.";
        }
    } else {
        $message = "Vous devez être connecté pour finaliser votre achat. Le paiement a été accepté mais aucun voyage n'a été enregistré.";
    }
} else {
    // Paiement refusé ou erreur
    $message = "Une erreur est survenue lors du traitement de votre paiement.";
}

// Nettoyer les variables de session liées à la transaction
unset($_SESSION['current_transaction']);

function ajouterVoyagesAchetesAData($panier, $utilisateur_id, $transaction_id, $transaction_date) {
    global $dataVoyages_file;
    
    // Créer le dossier data s'il n'existe pas
    $dir = dirname($dataVoyages_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Charger les données existantes
    $dataVoyages = [];
    if (file_exists($dataVoyages_file)) {
        $dataVoyages = json_decode(file_get_contents($dataVoyages_file), true) ?: [];
    }
    
    // Préparer les voyages achetés avec les informations de la transaction
    foreach ($panier as $voyage) {
        // Ajouter les informations de transaction
        $voyage['transaction_id'] = $transaction_id;
        $voyage['date_achat'] = $transaction_date;
        $voyage['utilisateur_id'] = $utilisateur_id;
        $voyage['status'] = 'payé';
        
        // Ajouter le voyage à dataVoyages.json
        $dataVoyages[] = $voyage;
    }
    
    // Sauvegarder dans le fichier JSON
    file_put_contents($dataVoyages_file, json_encode($dataVoyages, JSON_PRETTY_PRINT));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Transaction</title>
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
    <a href="accueil.php" class="button">Retour vers la page d'accueil</a>
</body>
</html>