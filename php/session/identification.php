<?php
session_start();
header('Content-Type: application/json');

// Receiving user data
$email = $_POST['Email'] ?? '';
$password = $_POST['Password'] ?? '';

// Path to the json
$relative_path = "../../data/utilisateurs.json";

// Verifying user's existence
$Content = file_get_contents($relative_path);
$Content = json_decode($Content, true);

// Check if email exists first
$emailExists = false;
$passwordCorrect = false;
$userAccount = null;

foreach($Content as $tab){
    if($tab['Email'] == $email) {
        $emailExists = true;
        // Verify password using password_verify for bcrypt
        if(password_verify($password, $tab['Password'])) {
            $passwordCorrect = true;
            $userAccount = $tab;
            break;
        }
    }
}

// Handle different cases
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
    // Store user info in session, excluding sensitive data
    $_SESSION["Prenom"] = $userAccount["Prenom"];
    $_SESSION["Nom"] = $userAccount["Nom"];
    $_SESSION["Email"] = $userAccount["Email"];
    $_SESSION["Club"] = $userAccount["Club"];
    $_SESSION["user"] = $userAccount["Id"];    
    $_SESSION["VIP"] = $userAccount["VIP"];
    $_SESSION["banni"] = $userAccount["banni"];
    // Don't store password in session
    
    echo json_encode(['success' => true, 'redirect' => 'accueil.php']);
    exit();
}
?>
