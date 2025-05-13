<?php
session_start();

$data_path = "../data/utilisateurs.json";

// Lire le contenu actuel du fichier
if (file_exists($data_path)) {
    $content = file_get_contents($data_path);
    // Si le fichier est vide, initialiser avec un tableau JSON
    if (empty($content)) {
        $jsonArray = [];
    } else {
        // Convertir le contenu en tableau PHP
        $jsonArray = json_decode($content, true);
        if ($jsonArray === null) {
            $jsonArray = []; // En cas d'erreur JSON
        }
    }
} else {
    $jsonArray = [];
}


?>