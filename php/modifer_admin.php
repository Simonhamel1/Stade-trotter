<?php
// modifer_admin.php - Script pour modifier les statuts VIP et Banni des utilisateurs
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ./connexion.php');
    exit;
}

// Charger les données utilisateurs
$jsonData = file_get_contents('../data/utilisateurs.json');
$users = json_decode($jsonData, true);

$userId = $_SESSION['user_id'];
$isAdmin = false;

// Vérifier si l'utilisateur existe dans le fichier JSON et est admin
foreach ($users as $user) {
    if ($user['Id'] == $userId) {
        $isAdmin = isset($user['VIP']) && $user['VIP'] === true;
        break;
    }
}

// Rediriger si l'utilisateur n'est pas admin
if (!$isAdmin) {
    header('Location: ./connexion.php');
    exit;
}

// Traiter les demandes de changement de statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['userId'])) {
    $action = $_POST['action'];
    $targetUserId = $_POST['userId'];
    
    // Rechercher l'utilisateur dans le tableau
    $userIndex = -1;
    foreach ($users as $index => $user) {
        if ($user['Id'] == $targetUserId) {
            $userIndex = $index;
            break;
        }
    }
    
    // Modifier le statut si l'utilisateur est trouvé
    if ($userIndex !== -1) {
        // Pour l'action "vip", basculer le statut VIP
        if ($action === 'vip') {
            $currentStatus = isset($users[$userIndex]['VIP']) ? $users[$userIndex]['VIP'] : false;
            $users[$userIndex]['VIP'] = !$currentStatus;
        }
        // Pour l'action "banni", basculer le statut banni
        else if ($action === 'banni') {
            $currentStatus = isset($users[$userIndex]['banni']) ? $users[$userIndex]['banni'] : false;
            $users[$userIndex]['banni'] = !$currentStatus;
        }
          // Enregistrer les modifications dans le fichier JSON
        try {
            // S'assurer que le répertoire est accessible en écriture
            if (!is_writable('../data')) {
                throw new Exception("Le répertoire data n'est pas accessible en écriture");
            }
              // Vérifier si le fichier existe et est accessible en écriture
            if (file_exists('../data/utilisateurs.json') && !is_writable('../data/utilisateurs.json')) {
                throw new Exception("Le fichier utilisateurs.json n'est pas accessible en écriture");
            }
            
            // Enregistrer les modifications dans le fichier JSON
            $jsonContent = json_encode($users, JSON_PRETTY_PRINT);
            if ($jsonContent === false) {
                throw new Exception("Erreur lors de l'encodage JSON : " . json_last_error_msg());
            }
            
            $result = file_put_contents('../data/utilisateurs.json', $jsonContent);
            if ($result === false) {
                throw new Exception("Erreur lors de l'écriture du fichier");
            }
            
            // Retourner une réponse de succès
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } catch (Exception $e) {
            // Retourner une réponse d'erreur
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    }
    
    exit;
}

// Rediriger vers la page admin si on accède directement à cette page
header('Location: ./admin.php');
exit;
?>