<?php
    session_start();

    // Vérification de la méthode HTTP
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header('Location: ../inscription.php');
        exit();
    }

    // Pour conserver les données en cas d'erreur
    if (isset($_POST['Prenom'])) $_SESSION['form_prenom'] = $_POST['Prenom'];
    if (isset($_POST['Nom'])) $_SESSION['form_nom'] = $_POST['Nom'];
    if (isset($_POST['Email'])) $_SESSION['form_email'] = $_POST['Email'];
    if (isset($_POST['Sexe'])) $_SESSION['form_sexe'] = $_POST['Sexe'];
    if (isset($_POST['Club'])) $_SESSION['form_club'] = $_POST['Club'];
    if (isset($_POST['Question'])) $_SESSION['form_question'] = $_POST['Question'];
    if (isset($_POST['Reponse'])) $_SESSION['form_reponse'] = $_POST['Reponse'];

    // Vérifier que tous les champs obligatoires sont présents
    $required_fields = ['Prenom', 'Nom', 'Email', 'Password', 'ConfirmPassword', 'Club', 'Sexe', 'Question', 'Reponse'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires";
            header('Location: ../inscription.php');
            exit();
        }
    }

    // Validation du format de l'email
    if (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format d'email invalide";
        header('Location: ../inscription.php');
        exit();
    }

    // Vérification que les mots de passe correspondent
    if ($_POST['Password'] !== $_POST['ConfirmPassword']) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas";
        header('Location: ../inscription.php');
        exit();
    }

    // Préparation des données utilisateur avec nettoyage
    $user_data = [
        "Prenom" => htmlspecialchars(trim($_POST["Prenom"])),
        "Nom" => htmlspecialchars(trim($_POST["Nom"])),
        "Email" => htmlspecialchars(trim($_POST["Email"])),
        "Sexe" => htmlspecialchars($_POST["Sexe"]),
        "Club" => htmlspecialchars($_POST["Club"]),
        "Question" => htmlspecialchars($_POST["Question"]),
        "Reponse" => htmlspecialchars(trim($_POST["Reponse"])),
        "VIP" => false,
        "banni" => false
    ];

    // Hashage sécurisé du mot de passe avec password_hash (meilleur que sha256)
    $password_hash = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $user_data["Password"] = $password_hash;

    // Génération de l'ID utilisateur (plus sécurisé)
    $unique_id = uniqid(rand(), true);
    $user_data["Id"] = hash('sha256', $unique_id . $_POST['Email']);

    //HASHAGE DE LA REPONSE et de la question
    $user_data["Reponse"] = password_hash($_POST['Reponse'], PASSWORD_DEFAULT);

    $absolute_path = "../../data/utilisateurs.json";
    
    // Lire le contenu actuel du fichier
    if (file_exists($absolute_path)) {
        $content = file_get_contents($absolute_path);
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

    // Vérifier que l'utilisateur n'a pas un compte existant
    foreach($jsonArray as $row) {
        if(isset($row["Email"]) && $row["Email"] == $_POST["Email"]) {
            // Ajouter le message d'erreur à la session
            $_SESSION['error_message'] = "Cette adresse email est déjà utilisée, veuillez vous connecter";
            header('Location: ../inscription.php');
            exit();
        }
    }

    // Ajouter les nouvelles données
    $jsonArray[] = $user_data;
    
    // Reconvertir en JSON et écrire dans le fichier
    if (file_put_contents($absolute_path, json_encode($jsonArray, JSON_PRETTY_PRINT)) === false) {
        $_SESSION['error_message'] = "Erreur lors de l'enregistrement, veuillez réessayer";
        header('Location: ../inscription.php');
        exit();
    }

    // Stockage minimal en session (uniquement ce qui est nécessaire)
    $_SESSION["user_id"] = $user_data["Id"];
    $_SESSION["user_email"] = $user_data["Email"];
    $_SESSION["user_name"] = $user_data["Prenom"] . " " . $user_data["Nom"];
    
    // Nettoyage des données temporaires du formulaire
    unset($_SESSION['form_prenom']);
    unset($_SESSION['form_nom']);
    unset($_SESSION['form_email']);
    unset($_SESSION['form_sexe']);
    unset($_SESSION['form_club']);
    unset($_SESSION['form_question']);
    unset($_SESSION['form_reponse']);
    
    // Redirection vers la page d'accueil
    header('Location:../accueil.php');
    exit();
?>
