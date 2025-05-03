<?php
session_start();
header('Content-Type: application/json');

// Réception des données utilisateur
$email = $_POST['Email'] ?? '';
$password = $_POST['Password'] ?? '';

// Chemin vers le fichier json
$relative_path = "../../data/utilisateurs.json";

// Vérification de l'existence de l'utilisateur
$Content = file_get_contents($relative_path);
$Content = json_decode($Content, true);

// Vérification de l'email et du mot de passe
$emailExists = false;
$passwordCorrect = false;
$userAccount = null;

foreach($Content as $tab){
    if($tab['Email'] == $email) {
        $emailExists = true;
        if(password_verify($password, $tab['Password'])) {
            $passwordCorrect = true;
            $userAccount = $tab;
            break;
        }
    }
}

// Gestion des différents cas
if(!$emailExists) {
    echo json_encode(['success' => false, 'message' => 'Adresse email inconnue.']);
    exit();
} else if(!$passwordCorrect) {
    echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
    exit();
} else if($userAccount['banni']) {
    echo json_encode(['success' => false, 'message' => 'Votre compte a été banni. Connexion impossible.']);
    exit();
} else {
    // Stockage des informations utilisateur dans la session, sans les données sensibles
    $_SESSION["Prenom"] = $userAccount["Prenom"];
    $_SESSION["Nom"] = $userAccount["Nom"];
    $_SESSION["Email"] = $userAccount["Email"];
    $_SESSION["Club"] = $userAccount["Club"];
    $_SESSION["user_id"] = $userAccount["Id"];    
    $_SESSION["VIP"] = $userAccount["VIP"];
    $_SESSION["banni"] = $userAccount["banni"];
    // Ne pas stocker le mot de passe dans la session
    
    echo json_encode(['success' => true, 'redirect' => 'accueil.php']);
    exit();
}
?>
