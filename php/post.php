<?php
    $_POST['Password'] = hash('sha256', $_POST['Password']);
    $Id = hash('sha256', hash('sha256', $_POST['Email']) . $_POST['Password']); 
    $DataArray = $_POST; 
    $DataArray["Id"] = $Id;
    $DataArray = json_encode($DataArray);
    $absolute_path = "../donnees/DB.json";
    
    // Lire le contenu actuel du fichier
    $content = file_get_contents($absolute_path);
    
    // Si le fichier est vide, initialiser avec un tableau JSON
    if (empty($content)) {
        $content = "[]";
    }
    
    // Convertir le contenu en tableau PHP
    $jsonArray = json_decode($content, true);
    
    // Ajouter les nouvelles données
    $jsonArray[] = json_decode($DataArray, true);
    
    // Reconvertir en JSON et écrire dans le fichier
    file_put_contents($absolute_path, json_encode($jsonArray, JSON_PRETTY_PRINT));
?>