<?php
    $_POST['Password'] = hash('sha256', $_POST['Password']);
    $post_json = json_encode($_POST);
    echo $post_json;
    // isn't able to write after the post method
    $fichier = fopen("./donnees/DB.json", "a+");
    fwrite($fichier, $post_json);
    fclose($fichier);
?>