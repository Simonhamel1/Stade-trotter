<?php
    session_start();
    // Getting all inscription infos
    $_SESSION["Prenom"]=$_POST["Prenom"];
    $_SESSION["Nom"]=$_POST["Nom"];
    $_SESSION["Email"]=$_POST["Email"];
    $_SESSION["Club"]=$_POST["Club"];
    $_POST['Password'] = hash('sha256', $_POST['Password']);
    $_SESSION["Password"]=$_POST["Password"];
    $_SESSION["VIP"] = false;
    $_SESSION["banni"] = false;

    // Hashing Id
    $Id = hash('sha256', hash('sha256', $_POST['Email']) . $_POST['Password']);  
    $_SESSION["user"] = $Id;

    $DataArray = $_POST; 
    $DataArray["Id"] = $Id;
    $DataArray = json_encode($DataArray);
    $absolute_path = "../../data/utilisateurs.json";
    
    // Lire le contenu actuel du fichier
    $content = file_get_contents($absolute_path);
    
    // Si le fichier est vide, initialiser avec un tableau JSON
    if (empty($content)) {
        $content = "[]";
    }
    
    // Convertir le contenu en tableau PHP
    $jsonArray = json_decode($content, true);

    // Vérifier que l'utilisateur n'a pas un compte
    foreach($jsonArray as $row){
        if($row["Id"] == $Id || $row["Email"] == $_POST["Email"]) {
            $_SESSION['error_message'] = "This email already has an account, please connect to it";
            header('Location: ../inscription.php'); // Redirect to your signup page
            exit();
        }
    }
    // Ajouter les nouvelles données
    $jsonArray[] = json_decode($DataArray, true);
    
    // Reconvertir en JSON et écrire dans le fichier
    file_put_contents($absolute_path, json_encode($jsonArray, JSON_PRETTY_PRINT));
    header('Location:../accueil.php');
?>