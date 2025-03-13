<?php
    // je veux vérifier les données de connexion pour créer une session
    session_start();
    // Vérifier l'existence d'une session pour l'utilisateur sinon créer nouvelle
    if (!isset($_SESSION['Id'])) {
        // Si l'utilisateur n'a pas encore de session, on la crée
        $_SESSION['Id'] = uniqid('user', true); // Génère un identifiant unique
        echo "Nouvelle session créée pour l'utilisateur : " . $_SESSION['Id'];
    } else {
        // Si la session existe déjà, on affiche l'ID utilisateur
        echo "Session existante détectée : " . $_SESSION['Id'];
    }
?>