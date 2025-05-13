<?php 
    session_start();

    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['Email'])) {
        header('Location: ./connexion.php');
        exit;
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
    } else {
        die("Le fichier de données des voyages n'existe pas.");
    }
    
    // Filtrer pour ne retourner que les voyages de l'utilisateur connecté
    $voyages_payes = [];
    if (!empty($voyages_data) && isset($_SESSION['user_id'])) {
        foreach ($voyages_data as $voyage) {
            if (isset($voyage['utilisateur_id']) && $voyage['utilisateur_id'] === $_SESSION['user_id']) {
                $voyages_payes[] = $voyage;
            }
        }
    }
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
    <?php require './header.php'; ?>
    
    <?php if (!empty($update_message)): ?>
        <div class="notification-container">
            <?php echo $update_message; ?>
        </div>
    <?php endif; ?>

    <section id="profil">
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
                            <option value="">Sélectionnez une question</option>
                            <option value="Nom de votre premier animal" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Nom de votre premier animal") ? 'selected' : ''; ?>>Nom de votre premier animal</option>
                            <option value="Nom de jeune fille de votre mère" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Nom de jeune fille de votre mère") ? 'selected' : ''; ?>>Nom de jeune fille de votre mère</option>
                            <option value="Ville de naissance" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Ville de naissance") ? 'selected' : ''; ?>>Ville de naissance</option>
                            <option value="Premier emploi" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Premier emploi") ? 'selected' : ''; ?>>Premier emploi</option>
                            <option value="Modèle de votre première voiture" <?php echo (isset($current_user['Question']) && $current_user['Question'] === "Modèle de votre première voiture") ? 'selected' : ''; ?>>Modèle de votre première voiture</option>
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
                        <button id="soumettre_button" onclick="soumettre_modification();" style="display: none;">
                            Soumettre les modifications
                        </button>
                    </div>
                </div>
                <div class="data" id="deconnexion">
                    <a href="./session/deconnexion.php">Déconnexion</a>
                </div>
            </div>

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
</body>
</html>