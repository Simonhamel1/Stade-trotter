<?php
// Receiving user datas
$_POST['Password'] = hash('sha256', $_POST['Password']);
$Id = hash('sha256', hash('sha256', $_POST['Email']) . $_POST['Password']);  

// Path to the json
$relative_path = "../../donnees/DB.json";

// Verifying user's existence
$Content = file_get_contents($relative_path);
$Content = json_decode($Content, true);

// Bug is here
foreach($Content as $tab){
    if($tab['Id'] == $Id) {                           
        // créer la session 
    header('Location:../accueil.php');  
    }
}

// header('Location:../inscription.php');
?>