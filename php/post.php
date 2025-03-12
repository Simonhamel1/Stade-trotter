<?php
    $_POST['Password'] = hash('sha256', $_POST['Password']);
    $post_json = json_encode($_POST);
    echo $post_json;
    // isn't able to write after the post method
    $absolute_path = __DIR__ . "/donnees/DB.json";
    $fichier = fopen($absolute_path, "a+");
    fwrite($fichier, $post_json);
    fclose($fichier);
?>