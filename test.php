<?php
    $Array = array('a' => 1,'ewan' => 2,'clabaut' => 3);
    $json = json_encode($Array);
    $absolute_path = __DIR__ . "/donnees/DB.json";
    $fichier = fopen($absolute_path, "a+");
    fwrite($fichier, $json);
    fclose($fichier);

// Bcrypt : Méthode de hashage
// json_encode : Transforme un tableau php en json
// json_encode($_POST) // attention à vérifier la variable peut être à retraiter
?>