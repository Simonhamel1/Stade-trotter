<?php
    $fields = ['Nom', 'Prénom', 'Email', 'Sexe', 'Password', 'Club'];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field != 'Password') {  // Ne pas afficher le mot de passe
                echo "<p style='margin: 10px 0;'><strong>" . $field . ":</strong> " 
                     . htmlspecialchars($_POST[$field]) . "</p>";
            }
        }
    }
    echo "</div>";
// Bcrypt : Méthode de hashage
// json_encode : Transforme un tableau php en json
// json_encode($_POST) // attention à vérifier la variable peut être à retraiter
?>