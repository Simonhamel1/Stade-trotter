<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['reset_email'])) {
    $email = $_SESSION['reset_email'];
    $answer = $_POST['answer'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Vérifier que les mots de passe correspondent
    if ($new_password !== $confirm_password) {
        header("Location: .security_question.php?error=" . urlencode("Les mots de passe ne correspondent pas."));
        exit();
    }
    
    // Chemin vers le fichier JSON des utilisateurs
    $file_path = "../../data/utilisateurs.json";
    
    // Vérifier si le fichier existe
    if (file_exists($file_path)) {
        // Lire le contenu du fichier
        $json_data = file_get_contents($file_path);
        
        // Convertir le JSON en tableau PHP
        $users = json_decode($json_data, true);
        
        // Variable pour stocker l'index de l'utilisateur trouvé
        $found_user_index = -1;
        
        // Rechercher l'utilisateur avec l'email correspondant
        foreach ($users as $index => $user) {
            if ($user['Email'] === $email) {
                $found_user_index = $index;
                break;
            }
        }
        
        // Si l'utilisateur est trouvé
        if ($found_user_index !== -1) {
            $user = $users[$found_user_index];
            
            // Vérifier la réponse à la question de sécurité
            if (password_verify($answer, $user['Reponse'])) {
                // Réponse correcte, mettre à jour le mot de passe
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Mettre à jour le mot de passe de l'utilisateur
                $users[$found_user_index]['Password'] = $hashed_password;
                
                // Enregistrer les modifications dans le fichier JSON
                if (file_put_contents($file_path, json_encode($users, JSON_PRETTY_PRINT))) {
                    // Effacer les données de session relatives à la réinitialisation
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['security_question']);
                    
                    // Rediriger vers la page de connexion avec un message de succès
                    header("Location: ../connexion.php?success=" . urlencode("Votre mot de passe a été réinitialisé avec succès."));
                    exit();
                } else {
                    header("Location: ./security_question.php?error=" . urlencode("Erreur lors de la mise à jour du mot de passe. Veuillez réessayer."));
                    exit();
                }
            } else {
                // Réponse incorrecte
                header("Location: ./security_question.php?error=" . urlencode("Réponse incorrecte à la question de sécurité."));
                exit();
            }
        } else {
            // Utilisateur non trouvé
            header("Location: ./mot_de_passe_oublie.php?error=" . urlencode("Adresse email non trouvée."));
            exit();
        }
    } else {
        // Le fichier des utilisateurs n'existe pas
        header("Location: ./mot_de_passe_oublie.php?error=" . urlencode("Erreur système. Veuillez contacter l'administrateur."));
        exit();
    }
} else {
    // Si on accède directement au script sans POST ou session valide
    header("Location: ../connexion.php");
    exit();
}
?>