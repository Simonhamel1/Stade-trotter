<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Chemin vers le fichier JSON des utilisateurs
    $file_path = "../../data/utilisateurs.json";
    
    // Vérifie si le fichier existe
    if (file_exists($file_path)) {
        // Lire le contenu du fichier
        $json_data = file_get_contents($file_path);
        
        // Convertir le JSON en tableau PHP
        $users = json_decode($json_data, true);
        
        // Variable pour stocker l'utilisateur trouvé
        $found_user = null;
        
        // Recherche l'utilisateur avec l'email correspondant
        foreach ($users as $user) {
            if ($user['Email'] === $email) {
                $found_user = $user;
                break;
            }
        }
        
        // Si l'utilisateur est trouvé et qu'il a une question de sécurité
        if ($found_user && isset($found_user['Question'])) {
            // Stocker l'email et la question dans la session
            $_SESSION['reset_email'] = $email;
            $_SESSION['security_question'] = $found_user['Question'];
            
            // Rediriger vers la page avec la question de sécurité
            header("Location: ./security_question.php");
            exit();
        } else {
            // Si l'utilisateur n'est pas trouvé ou n'a pas de question de sécurité
            header("Location: ./mot_de_passe_oublie.php?error=" . urlencode("Adresse email non trouvée ou question de sécurité non configurée."));
            exit();
        }
    } else {
        // Le fichier des utilisateurs n'existe pas
        header("Location: ./mot_de_passe_oublie.php?error=" . urlencode("Erreur système. Veuillez contacter l'administrateur."));
        exit();
    }
} else {
    // Si on accède directement au script sans POST
    header("Location: ../connexion.php");
    exit();
}
?>