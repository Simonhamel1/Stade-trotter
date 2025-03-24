<?php
    // je veux vérifier les données de connexion pour créer une session
    session_start();
    // Vérifier l'existence d'une session pour l'utilisateur sinon créer nouvelle
    if (!isset($_SESSION['Id'])) {
        header("Location:./inscription.php");
    } else {
        // Si la session existe déjà, on affiche l'ID utilisateur
        echo "Session existante détectée : " . $_SESSION['Id'];
    }
?>