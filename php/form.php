<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = ['Nom', 'Prénom', 'Email', 'Sexe', 'Password', 'Club'];
    echo "<div style='width: 300px; margin: 20px auto; padding: 15px; border: 1px solid #ccc; border-radius: 5px;'>";
    echo "<h2 style='text-align: center;'>Informations soumises</h2>";
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field != 'Password') {  // Ne pas afficher le mot de passe
                echo "<p style='margin: 10px 0;'><strong>" . $field . ":</strong> " 
                     . htmlspecialchars($_POST[$field]) . "</p>";
            }
        }
    }
    echo "</div>";
}
?>