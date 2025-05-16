<?php
// check_ban.php - Vérifie si l'utilisateur connecté est banni
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'utilisateur est connecté, vérifier s'il est banni
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Charger les données utilisateurs
    $jsonData = file_get_contents('../data/utilisateurs.json');
    $users = json_decode($jsonData, true);
      // Vérifier si l'utilisateur est banni
    foreach ($users as $user) {
        if ($user['Id'] == $userId) {
            // Si l'utilisateur est banni, le déconnecter
            if (isset($user['banni']) && $user['banni'] === true) {
                // Effacer les variables de session
                $_SESSION = array();
                
                // Détruire le cookie de session
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params["path"], $params["domain"],
                        $params["secure"], $params["httponly"]
                    );
                }
                
                // Détruire la session
                session_destroy();
                
                // Rediriger vers la page de connexion avec un message d'erreur
                // Vérifier si on est déjà sur la page de connexion pour éviter une redirection en boucle
                $currentPage = basename($_SERVER['PHP_SELF']);
                if ($currentPage !== 'connexion.php') {
                    header("Location: connexion.php?error=banned");
                    exit;
                }
            }
            break;
        }
    }
}
?>
