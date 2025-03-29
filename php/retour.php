<?php
session_start();

if(isset($_GET['status']) && $_GET['status'] == 'denied'){
    $_SESSION['transaction_status'] = 'denied';
}   
$absolute_path_paiements = "../data/paiements.json";
$absolute_path_dataVoyages = "../data/dataVoyages.json";

// Lire le contenu actuel du fichier
$content_paiement = file_exists($absolute_path_paiements) ? file_get_contents($absolute_path_paiements) : '[]';
$content_voyage = file_exists($absolute_path_dataVoyages) ? file_get_contents($absolute_path_dataVoyages) : '[]';

// Convertir le contenu en tableau PHP
$jsonArrayPaiement = json_decode($content_paiement, true);
$jsonArrayVoyage = json_decode($content_voyage, true);
// Tableau des informations de paiements
$userData = [
    'status' => isset($_GET['status']) ? $_GET['status'] : null,
    'montant' => isset($_GET['montant']) ? $_GET['montant'] : null,
    'transaction' => isset($_GET['transaction']) ? $_GET['transaction'] : null,
    'vendeur' => isset($_GET['vendeur']) ? $_GET['vendeur'] : null,
    'control' => isset($_GET['control']) ? $_GET['control'] : null,
    'user' => array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null
];
$jsonArrayPaiement[] = $userData;
// Tableau des informations de voyages
if (isset($_SESSION['voyage_data'])) {
    $jsonArrayVoyage[] = $_SESSION['voyage_data'];
}

// Ajoute les infos aux json
file_put_contents($absolute_path_paiements, json_encode($jsonArrayPaiement, JSON_PRETTY_PRINT));
file_put_contents($absolute_path_dataVoyages, json_encode($jsonArrayVoyage, JSON_PRETTY_PRINT));
unset($_SESSION['voyage_id']);
unset($_SESSION['voyage_data']);
?>



<!DOCTYPE html>
<html lang="en">

<style>
    body {
        
        padding: 0;
        font-family: Arial, sans-serif; 
        background-size: cover;
        background-position: center;
        color: #fff;
        text-align: center;
        color : #000;
    }

    h1 {
        margin-top: 20%;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    p {
        font-size: 1.2rem;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        text-align: center;
        margin: 20px auto;
        color: #000;
    }

    a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        transition: background-color 0.3s ease;
        color : #000;
    }

    a:hover {
        background-color: #0056b3;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation</title>
</head>
<body>
    <?php if (isset($_GET['status']) && $_GET['status'] == 'accepted'): ?>
        <h1>Paiement accepté !</h1>
        <p>Merci pour votre paiement. Voici un récapitulatif de votre transaction :</p>
        <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
            <li><strong>Montant :</strong> <?php echo htmlspecialchars($_GET['montant'] ?? 'N/A'); ?> €</li>
            <li><strong>Transaction ID :</strong> <?php echo htmlspecialchars($_GET['transaction'] ?? 'N/A'); ?></li>
            <li><strong>Vendeur :</strong> <?php echo htmlspecialchars($_GET['vendeur'] ?? 'N/A'); ?></li>
        </ul>
        <p></p>
        <a href="./accueil.php">Retour à l'accueil</a>
    <?php else: ?>
        <h1>Le paiement a échoué.</h1>
        <p>Nous sommes désolés pour ce désagrément. Veuillez réessayer le paiement</p>
        <a href="javascript:history.go(-2)">Retour au paiement</a>
    <?php endif; ?>
</body>
</html>