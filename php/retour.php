<?php
session_start();

if(isset($_GET['status']) && $_GET['status'] == 'denied'){
    $_SESSION['transaction_status'] = 'denied';
    header('Location:./recap.php');
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
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-image: url('../photo/acceuil/arriere-plan.jpeg'); /* Assurez-vous que l'image existe à cet emplacement */
        background-size: cover;
        background-position: center;
        color: #fff;
        text-align: center;
    }

    h1 {
        margin-top: 20%;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    p {
        font-size: 1.2rem;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
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
        <script>
            setTimeout(function() {
                window.location.href = "./accueil.php";
            }, 1);
        </script>
    <?php else: ?>
        <h1>Le paiement a échoué.</h1>
        <p>Nous sommes désolés pour ce désagrément. Veuillez réessayer le paiement</p>
        <a href="javascript:history.go(-2)">Retour</a>
    <?php endif; ?>
</body>
</html>