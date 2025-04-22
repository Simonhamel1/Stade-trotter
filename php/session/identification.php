<?php
session_start();
// Receiving user datas    
$_POST['Password'] = hash('sha256', $_POST['Password']);
$Id = hash('sha256', hash('sha256', $_POST['Email']) . $_POST['Password']);   

// Path to the json
$relative_path = "../../data/utilisateurs.json";

// Verifying user's existence
$Content = file_get_contents($relative_path);
$Content = json_decode($Content, true);

foreach($Content as $tab){
    if($tab['Id'] == $Id) {    
        // Check if user is banned
        if($tab["banni"]) {
            $_SESSION['error_message'] = "Votre compte a été banni. Connexion impossible.";
            header('Location:../connexion.php');
            exit();
        }
        
        // Getting all inscription infos
        $_SESSION["Prenom"]=$tab["Prenom"];
        $_SESSION["Nom"]=$tab["Nom"];
        $_SESSION["Email"]=$tab["Email"];
        $_SESSION["Club"]=$tab["Club"];
        $_SESSION["Password"]=$tab["Password"];
        $_SESSION["user"] = $tab["Id"];    
        $_SESSION["VIP"] = $tab["VIP"];
        $_SESSION["banni"] = $tab["banni"];                   
        header('Location:../accueil.php'); 
        exit();
    }
}
$_SESSION['error_message'] = "This account doesn't exist or the password isn't right";
header('Location:../connexion.php');

exit();
?>
