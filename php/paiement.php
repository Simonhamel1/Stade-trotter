<?php
// Traitement du formulaire de paiement
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $card_number = trim($_POST['card_number'] ?? '');
    $card_holder = trim($_POST['card_holder'] ?? '');
    $expiration  = trim($_POST['expiration'] ?? '');
    $cvv         = trim($_POST['cvv'] ?? '');

    // Validation de base des champs
    if (empty($card_number) || strlen(preg_replace('/\D/', '', $card_number)) < 13) {
        $errors[] = "Numéro de carte invalide.";
    }
    if (empty($card_holder)) {
        $errors[] = "Le nom du titulaire est requis.";
    }
    if (empty($expiration)) {
        $errors[] = "La date d'expiration est requise.";
    }
    if (empty($cvv) || !ctype_digit($cvv) || (strlen($cvv) !== 3 && strlen($cvv) !== 4)) {
        $errors[] = "CVV invalide.";
    }

    // Si aucune erreur, simuler le paiement
    if (empty($errors)) {
        // Ici vous pourriez intégrer l'API de votre prestataire de paiement
        $success = "Paiement effectué avec succès (simulation).";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement par Carte Bancaire</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        label { display: block; margin-top: 10px; }
        input { padding: 5px; width: 300px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Paiement par Carte Bancaire</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif ?>

    <form action="" method="POST">
        <label for="card_number">Numéro de carte :</label>
        <input type="text" name="card_number" id="card_number" required>

        <label for="card_holder">Titulaire de la carte :</label>
        <input type="text" name="card_holder" id="card_holder" required>

        <label for="expiration">Date d'expiration (MM/AA) :</label>
        <input type="text" name="expiration" id="expiration" placeholder="MM/AA" required>

        <label for="cvv">CVV :</label>
        <input type="text" name="cvv" id="cvv" required>

        <button type="submit" style="margin-top: 15px;">Payer</button>
    </form>
</body>
</html>