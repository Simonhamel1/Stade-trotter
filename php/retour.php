<?php
session_start();
if($_GET['status'] == 'denied'){
    $_SESSION['transaction_status'] = 'denied';
    header('Location:./recap.php');
}   
$absolute_path_paiements = "../data/paiements.json";

// Lire le contenu actuel du fichier
$content = file_get_contents($absolute_path_paiements);

// Convertir le contenu en tableau PHP
$jsonArray = json_decode($content, true);
// Tableau des informations de paiements
$userData = $_GET;
$userData['user'] = $_SESSION['user'];
$jsonArray[] = $userData;

// Ajoute les infos aux json
file_put_contents($absolute_path_paiements, json_encode($jsonArray, JSON_PRETTY_PRINT));
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