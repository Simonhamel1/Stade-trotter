<?php
session_start();
if($_GET['status'] == 'denied'){
    $_SESSION['transaction_status'] = 'denied';
    header('Location:./recap.php');
}   
$absolute_path_paiements = "../data/paiements.json";
$absolute_path_dataVoyages = "../data/dataVoyages.json";

// Lire le contenu actuel du fichier
$content_paiement = file_get_contents($absolute_path_paiements);
$content_voyage = file_get_contents($absolute_path_dataVoyages);

// Convertir le contenu en tableau PHP
$jsonArrayPaiement = json_decode($content_paiement, true);
$jsonArrayVoyage = json_decode($content_voyage, true);
// Tableau des informations de paiements
$userData = $_GET;
$userData['user'] = $_SESSION['user'];
$jsonArrayPaiement[] = $userData;
// Tableau des informations de voyages
$jsonArrayVoyage[] = $_SESSION['voyage_data'];

// Ajoute les infos aux json
file_put_contents($absolute_path_paiements, json_encode($jsonArrayPaiement, JSON_PRETTY_PRINT));
file_put_contents($absolute_path_dataVoyages, json_encode($jsonArrayVoyage, JSON_PRETTY_PRINT));
unset($_SESSION['voyage_id']);
unset($_SESSION['voyage_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation</title>
</head>
<body>
    <h1>it worked</h1>
    <!-- Simon tu dois finir cette page -->
</body>
</html>