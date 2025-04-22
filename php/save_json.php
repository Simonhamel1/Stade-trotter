<?php
// Récupérer les données JSON envoyées
$jsonData = file_get_contents('php://input');
$users = json_decode($jsonData, true);

// Vérifier si les données sont valides
if ($users === null) {
    echo "Format JSON invalide";
    exit;
}

// Chemin vers le fichier JSON
$usersJsonFile = '../data/utilisateurs.json';

// Enregistrer les données dans le fichier
if (file_put_contents($usersJsonFile, json_encode($users, JSON_PRETTY_PRINT))) {
    echo "success";
} else {
    echo "Erreur lors de l'enregistrement du fichier";
}
?>