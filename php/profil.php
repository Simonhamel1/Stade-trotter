<?php 
    session_start();
    
    // Buffer de sortie pour éviter les erreurs "headers already sent"
    ob_start();

    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['Email'])) {
        header('Location: ./connexion.php');
        exit;
    }
    
    // Stocker les messages dans des variables plutôt que de les afficher directement
    $debug_messages = '';
    if (isset($_SESSION['user'])) {
        $debug_messages .= '<script>console.log("Utilisateur connecté : ' . $_SESSION['user'] . '");</script>';
    } 
    
    // Chargement des données des utilisateurs depuis le fichier JSON
    $users_json_file = '../data/utilisateurs.json';
    $users_data = [];
    
    if (file_exists($users_json_file)) {
        $users_data = json_decode(file_get_contents($users_json_file), true);
    } else {
        die("Le fichier de données des utilisateurs n'existe pas.");
    }

    // Chargement des données des voyages depuis le fichier JSON
    $voyages_json_file = '../data/dataVoyages.json';
    $voyages_data = [];
    
    if (file_exists($voyages_json_file)) {
        $voyages_data = json_decode(file_get_contents($voyages_json_file), true);
        // Stocker le debug dans une variable au lieu de l'afficher immédiatement
        $debug_messages .= '<script>';
        $debug_messages .= 'console.log("Données des voyages:");';
        $debug_messages .= 'console.log(' . json_encode($voyages_data, JSON_PRETTY_PRINT) . ');';
        $debug_messages .= '</script>';
    } else {
        die("Le fichier de données des voyages n'existe pas.");
    }
    
    // Filtrer pour ne retourner que les voyages de l'utilisateur connecté
    $voyages_payes = [];
    if (!empty($voyages_data) && isset($_SESSION['user'])) {
        foreach ($voyages_data as $voyage) {
            if (isset($voyage['utilisateur_id']) && $voyage['utilisateur_id'] === $_SESSION['user']) {
                $voyages_payes[] = $voyage;
            }
        }
    }

    // Fonction pour mettre à jour les données utilisateur dans le fichier JSON
    function update_user_info($user_id, $email, $prenom, $nom, $club, $sexe, $question, $reponse) {
        global $users_data, $users_json_file, $voyages_json_file, $voyages_data;
        
        $user_updated = false;
        
        foreach ($users_data as $key => $user) {
            if ($user['Id'] === $user_id) {
                // Sauvegarde des anciennes valeurs pour mise à jour des voyages
                $old_club = isset($user['Club']) ? $user['Club'] : '';
                $old_email = isset($user['Email']) ? $user['Email'] : '';
                $old_prenom = isset($user['Prenom']) ? $user['Prenom'] : '';
                $old_nom = isset($user['Nom']) ? $user['Nom'] : '';
                
                // Mise à jour des champs modifiés dans le tableau utilisateurs
                if (!empty($email)) $users_data[$key]['Email'] = $email;
                if (!empty($prenom)) $users_data[$key]['Prenom'] = $prenom;
                if (!empty($nom)) $users_data[$key]['Nom'] = $nom;
                
                // Pour les champs select, traiter même si c'est la même valeur
                $users_data[$key]['Club'] = $club; // Toujours mettre à jour le club
                $users_data[$key]['Sexe'] = $sexe; // Toujours mettre à jour le sexe
                
                if (!empty($question)) $users_data[$key]['Question'] = $question;
                if (!empty($reponse)) {
                    // Hasher la réponse avant de la stocker
                    $users_data[$key]['Reponse'] = password_hash($reponse, PASSWORD_DEFAULT);
                }
                
                // Enregistrer les modifications dans le fichier JSON utilisateurs
                $write_result = file_put_contents($users_json_file, json_encode($users_data, JSON_PRETTY_PRINT));
                
                if ($write_result === false) {
                    error_log("Erreur lors de l'écriture dans le fichier JSON des utilisateurs");
                    return false;
                }
                
                // Mettre à jour les données de la session
                $_SESSION['Email'] = $users_data[$key]['Email'];
                $_SESSION['Prenom'] = $users_data[$key]['Prenom'];
                $_SESSION['Nom'] = $users_data[$key]['Nom'];
                $_SESSION['Club'] = $users_data[$key]['Club']; // Mise à jour du club en session
                $_SESSION['Sexe'] = $users_data[$key]['Sexe']; // Mise à jour du sexe en session
                if (!empty($question)) $_SESSION['Question'] = $users_data[$key]['Question'];
                
                $user_updated = true;
                
                // Mise à jour des informations utilisateur dans les voyages
                if (!empty($voyages_data)) {
                    $voyages_updated = false;
                    
                    foreach ($voyages_data as $v_key => $voyage) {
                        if (isset($voyage['utilisateur_id']) && $voyage['utilisateur_id'] === $user_id) {
                            // Mise à jour des informations utilisateur dans le voyage
                            if (!empty($email) && isset($voyage['user_email'])) {
                                $voyages_data[$v_key]['user_email'] = $email;
                                $voyages_updated = true;
                            }
                            
                            if (!empty($prenom) && isset($voyage['user_prenom'])) {
                                $voyages_data[$v_key]['user_prenom'] = $prenom;
                                $voyages_updated = true;
                            }
                            
                            if (!empty($nom) && isset($voyage['user_nom'])) {
                                $voyages_data[$v_key]['user_nom'] = $nom;
                                $voyages_updated = true;
                            }
                            
                            // Mise à jour du club si c'est le stade du voyage
                            if (isset($voyage['stade']) && $voyage['stade'] === $old_club) {
                                $voyages_data[$v_key]['stade'] = $club;
                                $voyages_updated = true;
                            }
                            
                            // Mise à jour du nom complet si présent
                            if (((!empty($prenom) || !empty($nom))) && isset($voyage['user_fullname'])) {
                                $nouveau_prenom = !empty($prenom) ? $prenom : $old_prenom;
                                $nouveau_nom = !empty($nom) ? $nom : $old_nom;
                                $voyages_data[$v_key]['user_fullname'] = $nouveau_prenom . ' ' . $nouveau_nom;
                                $voyages_updated = true;
                            }
                        }
                    }
                    
                    // Enregistrer les modifications des voyages si nécessaire
                    if ($voyages_updated) {
                        $voyages_write = file_put_contents($voyages_json_file, json_encode($voyages_data, JSON_PRETTY_PRINT));
                        if ($voyages_write === false) {
                            error_log("Erreur lors de l'écriture dans le fichier JSON des voyages");
                            // Ne pas échouer pour cette étape
                        }
                    }
                }
                
                break;
            }
        }
        
        return $user_updated;
    }

    // Traitement des modifications du profil si formulaire soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        // Récupérer les valeurs du formulaire
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $club = isset($_POST['club']) ? $_POST['club'] : null;
        $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : null;
        $question = isset($_POST['question']) ? $_POST['question'] : '';
        $reponse = isset($_POST['reponse']) ? $_POST['reponse'] : '';
        
        // Log pour débogage
        error_log("Données soumises: email=$email, prenom=$prenom, nom=$nom, club=$club, sexe=$sexe");
        
        // Mise à jour des données utilisateur
        if (update_user_info($_SESSION['user'], $email, $prenom, $nom, $club, $sexe, $question, $reponse)) {
            // Redirection pour éviter les re-soumissions de formulaire
            header('Location: profil.php?updated=1');
            exit;
        } else {
            // En cas d'erreur
            header('Location: profil.php?error=1');
            exit;
        }
    }

    // Récupération des données complètes de l'utilisateur connecté
    $current_user = null;
    foreach ($users_data as $user) {
        if (isset($user['Id']) && $user['Id'] === $_SESSION['user']) {
            $current_user = $user;
            break;
        }
    }

    // Message de confirmation après mise à jour
    $update_message = '';
    if (isset($_GET['updated']) && $_GET['updated'] == 1) {
        $update_message = '<div class="alert success">Vos informations ont été mises à jour avec succès.</div>';
    } elseif (isset($_GET['error']) && $_GET['error'] == 1) {
        $update_message = '<div class="alert error">Une erreur est survenue lors de la mise à jour de vos informations.</div>';
    }
    
    // Vider le buffer de sortie pour éviter les problèmes de headers
    ob_end_clean();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/profil.css">
    <script type='text/javascript' src='../js/modification_utilisateurs.js'></script>
</head>
<body>
    <?php include './header.php'; ?>
    
    <?php if (!empty($update_message)): ?>
        <div class="notification-container">
            <?php echo $update_message; ?>
        </div>
    <?php endif; ?>

    <section id="profil">
        <form id="update-profile-form" method="POST" action="profil.php">
            <input type="hidden" name="action" value="update_profile">
            
            <!-- COLONNE DE GAUCHE : données utilisateur -->
            <div id="profil-left">
                <div class="data">
                    <h2>Email</h2>
                    <div class="modification">
                        <input type="email" id="inputbutton1" name="email"
                            value="<?php echo htmlspecialchars($_SESSION['Email']); ?>"
                            readonly>
                        <div class="modification_button" id="button1">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button1');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>Prénom</h2>
                    <div class="modification">
                        <input type="text" id="inputbutton2" name="prenom"
                            value="<?php echo htmlspecialchars($_SESSION['Prenom']); ?>"
                            readonly>                
                        <div class="modification_button" id="button2">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button2');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>Nom</h2>
                    <div class="modification">
                        <input type="text" id="inputbutton3" name="nom"
                            value="<?php echo htmlspecialchars($_SESSION['Nom']); ?>"
                            readonly>
                        <div class="modification_button" id="button3">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button3');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">  
                    <h2>Club</h2>
                    <div class="modification">
                        <select id="inputbutton4" name="club" disabled>
                            <option value="PSG" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'PSG') ? 'selected' : ''; ?>>PSG</option>
                            <option value="Real Madrid" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Real Madrid') ? 'selected' : ''; ?>>Real Madrid</option>
                            <option value="Barcelone" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Barcelone') ? 'selected' : ''; ?>>FC Barcelone</option>
                            <option value="Bayern" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Bayern') ? 'selected' : ''; ?>>Bayern Munich</option>
                            <option value="Manchester United" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Manchester United') ? 'selected' : ''; ?>>Manchester United</option>
                            <option value="Liverpool" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Liverpool') ? 'selected' : ''; ?>>Liverpool</option>
                            <option value="Chelsea" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Chelsea') ? 'selected' : ''; ?>>Chelsea</option>
                            <option value="Manchester City" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Manchester City') ? 'selected' : ''; ?>>Manchester City</option>
                            <option value="Juventus" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Juventus') ? 'selected' : ''; ?>>Juventus</option>
                            <option value="AC Milan" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'AC Milan') ? 'selected' : ''; ?>>AC Milan</option>
                            <option value="Borussia Dortmund" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Borussia Dortmund') ? 'selected' : ''; ?>>Borussia Dortmund</option>
                            <option value="Autre" <?php echo (isset($_SESSION['Club']) && $_SESSION['Club'] == 'Autre') ? 'selected' : ''; ?>>Autre</option>
                        </select>
                        <div class="modification_button" id="button4">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button4');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>Sexe</h2>
                    <div class="modification">
                        <select id="inputbutton5" name="sexe" disabled>
                            <option value="Homme" <?php echo (isset($_SESSION['Sexe']) && $_SESSION['Sexe'] === 'Homme') ? 'selected' : ''; ?>>Homme</option>
                            <option value="Femme" <?php echo (isset($_SESSION['Sexe']) && $_SESSION['Sexe'] === 'Femme') ? 'selected' : ''; ?>>Femme</option>
                            <option value="Autre" <?php echo (isset($_SESSION['Sexe']) && $_SESSION['Sexe'] === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                        </select>
                        <div class="modification_button" id="button5">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button5');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>Question de sécurité</h2>
                    <div class="modification">
                        <select id="inputbutton6" name="question" disabled>
                            <option value="Quel est le nom de votre premier animal de compagnie ?" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Quel est le nom de votre premier animal de compagnie ?") ? 'selected' : ''; ?>>Quel est le nom de votre premier animal de compagnie ?</option>
                            <option value="Quel est votre film préféré ?" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Quel est votre film préféré ?") ? 'selected' : ''; ?>>Quel est votre film préféré ?</option>
                            <option value="Dans quelle ville êtes-vous né(e) ?" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Dans quelle ville êtes-vous né(e) ?") ? 'selected' : ''; ?>>Dans quelle ville êtes-vous né(e) ?</option>
                            <option value="Quel est le prénom de votre meilleur(e) ami(e) d\'enfance ?" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Quel est le prénom de votre meilleur(e) ami(e) d\'enfance ?") ? 'selected' : ''; ?>>Quel est le prénom de votre meilleur(e) ami(e) d'enfance ?</option>
                            <option value="Quelle est votre couleur préférée ?" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Quelle est votre couleur préférée ?") ? 'selected' : ''; ?>>Quelle est votre couleur préférée ?</option>
                        </select>
                        <div class="modification_button" id="button6">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button6');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>Réponse de sécurité</h2>
                    <div class="modification">
                        <input type="password" id="inputbutton7" name="reponse"
                            placeholder="Nouvelle réponse de sécurité"
                            readonly>
                        <div class="modification_button" id="button7">
                            <button type="button" class="permettre_modifications"
                                onclick="modification_utilisateurs('button7');"
                                value="modifier">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="data">
                    <h2>VIP</h2>
                    <p>
                        <?php
                            echo (isset($_SESSION['VIP']) && $_SESSION['VIP']===true)
                                ? 'Vous êtes un membre VIP'
                                : 'Non';
                        ?>
                    </p>
                    <div id="end_buttons">
                        <?php if(isset($_SESSION['VIP']) && $_SESSION['VIP']===true): ?>
                            <a href="./admin.php" class="admin-button">
                                Accéder à la page Admin
                            </a>
                        <?php endif; ?>
                        <button type="submit" id="soumettre_button" style="display: none;">
                            Soumettre les modifications
                        </button>
                    </div>
                </div>
                <div class="data" id="deconnexion">
                    <a href="./session/deconnexion.php">Déconnexion</a>
                </div>
            </div>
        </form>

        <!-- COLONNE DE DROITE : liste des voyages payés -->
        <div id="profil-right">
            <h2>Mes voyages payés</h2>
            <div class="voyages-list">
                <?php if (empty($voyages_payes)): ?>
                    <p class="no-voyages">Aucun voyage payé pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($voyages_payes as $voyage): ?>
                        <div class="voyage-card">
                            <h3><?php echo htmlspecialchars($voyage['voyage_name']); ?></h3>
                            <div class="voyage-details">
                                <div class="voyage-info">
                                    <p><span>Dates :</span> <?php echo date('d/m/Y', strtotime($voyage['date_depart'])); ?> - <?php echo date('d/m/Y', strtotime($voyage['date_retour'])); ?></p>
                                    <p><span>Participants :</span> <?php echo $voyage['nb_participants']; ?></p>
                                    <p><span>Prix total :</span> <?php echo number_format($voyage['final_price'], 2, ',', ' '); ?> €</p>
                                </div>
                                <a href="voyage-details.php?id=<?php echo urlencode($voyage['voyage_id']); ?>&name=<?php echo urlencode($voyage['voyage_name']); ?>&date_depart=<?php echo urlencode($voyage['date_depart']); ?>&date_retour=<?php echo urlencode($voyage['date_retour']); ?>&prix=<?php echo urlencode($voyage['final_price']); ?>" class="btn-details">Voir détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="destinations.php" class="btn-recap">Commander un autre voyage</a>
        </div>
    </section>

    <?php include '../php/footer.php'; ?>
    
    <script>
        // Fonction pour afficher le bouton de soumission quand des champs sont modifiés
        function checkModifiedFields() {
            const inputs = document.querySelectorAll('input:not([readonly]), select:not([disabled])');
            const submitButton = document.getElementById('soumettre_button');
            
            if (inputs.length > 0) {
                submitButton.style.display = 'block';
            } else {
                submitButton.style.display = 'none';
            }
        }

        // Mettre à jour la fonction de modification pour vérifier les champs modifiés
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier périodiquement si des champs sont modifiables
            setInterval(checkModifiedFields, 500);
            
            // Afficher le message de succès ou d'erreur
            <?php if (!empty($update_message)): ?>
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 1000);
                });
            }, 3000);
            <?php endif; ?>
            
            // S'assurer que les menus déroulants sont activés avant soumission
            document.getElementById('update-profile-form').addEventListener('submit', function(e) {
                // Activer tous les select pour qu'ils soient envoyés
                document.querySelectorAll('select').forEach(function(select) {
                    select.disabled = false;
                });
                
                // Log pour vérifier les valeurs avant envoi
                console.log("Club sélectionné:", document.getElementById('inputbutton4').value);
                console.log("Sexe sélectionné:", document.getElementById('inputbutton5').value);
            });
            
            // Debug: Afficher les valeurs actuelles
            console.log("Club actuel: <?php echo isset($_SESSION['Club']) ? $_SESSION['Club'] : 'Non défini'; ?>");
            console.log("Sexe actuel: <?php echo isset($_SESSION['Sexe']) ? $_SESSION['Sexe'] : 'Non défini'; ?>");
        });
    </script>
    
    <?php echo $debug_messages; ?>
</body>
</html>