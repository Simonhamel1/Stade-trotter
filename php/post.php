<?php
    $_POST['Password'] = hash('sha256', $_POST['Password']);
    $Id = hash('sha256', hash('sha256', $_POST['Email']) . $_POST['Password']); // a746a7c5d2a5eee0dded7a4876bb2e7f9468f678c2d43313bdd89371731ca5bb
    $DataArray = $_POST; 
    $DataArray["Id"] = $Id;
    $DataArray = json_encode($DataArray);
    echo $DataArray;
    // isn't able to write after the post method
    $absolute_path = __DIR__ . "/donnees/DB.json";
    $fichier = fopen($absolute_path, "a+");
    fwrite($fichier, $DataArray);
    fclose($fichier);
?>