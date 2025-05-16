<?php
session_start();

$data_path = "../data/utilisateurs.json";

// Lire le contenu actuel du fichier
if (file_exists($data_path)) {
    $content = file_get_contents($data_path);
    // Si le fichier est vide, initialiser avec un tableau JSON
    if (empty($content)) {
        $jsonArray = [];
    } else {
        // Convertir le contenu en tableau PHP
        $jsonArray = json_decode($content, true);
        if ($jsonArray === null) {
            $jsonArray = []; // En cas d'erreur JSON
        }
    }
} else {
    $jsonArray = [];
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit();
}

$userId = $_SESSION['user_id'];
$userUpdated = false;

// Parcourir le tableau des utilisateurs
for ($i = 0; $i < count($jsonArray); $i++) {
    if (isset($jsonArray[$i]['Id']) && $jsonArray[$i]['Id'] === $userId) {
        // Traiter tous les paramètres GET
        foreach ($_GET as $key => $value) {
            // Si le paramètre est 'Password', il faut le hasher
            if (strtolower($key) === 'password') {
                $jsonArray[$i][$key] = password_hash($value, PASSWORD_DEFAULT);
            } 
            // Si le paramètre est 'Reponse', il faut le hasher également
            else if (strtolower($key) === 'reponse') {
                $jsonArray[$i][$key] = password_hash($value, PASSWORD_DEFAULT);
            }
            // Pour les autres paramètres, simple mise à jour
            else {
                // Première lettre en majuscule pour respecter le format existant
                $formattedKey = ucfirst(strtolower($key));
                $jsonArray[$i][$formattedKey] = htmlspecialchars(trim($value));
                
                // Mettre à jour les données de session si nécessaire
                if (in_array($formattedKey, ['Prenom', 'Nom', 'Email'])) {
                    $_SESSION[$formattedKey] = htmlspecialchars(trim($value));
                }
            }
        }
        $userUpdated = true;
        break;
    }
}

// Sauvegarder les modifications dans le fichier JSON
if ($userUpdated) {
    if (file_put_contents($data_path, json_encode($jsonArray, JSON_PRETTY_PRINT)) !== false) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde des données']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
}
?>