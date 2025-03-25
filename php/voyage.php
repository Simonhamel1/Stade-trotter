<?php
session_start();
// Chargement des voyages depuis le fichier JSON
$jsonFile = __DIR__ . '/../data/voyages.json';
if (!file_exists($jsonFile)) {
  die("Le fichier de données voyages n'existe pas.");
}

$jsonData = file_get_contents($jsonFile);
$voyages = json_decode($jsonData, true);
if (!$voyages) {
  die("Erreur lors du décodage du fichier JSON.");
}

// Récupérer l'identifiant du voyage depuis l'URL
$id = $_GET['destination'] ?? '';
if (!$id || !isset($voyages[$id])) {
  echo "<p>Voyage non trouvé. <a href='destinations.php'>Retour aux destinations</a></p>";
  exit();
}
$voyage = $voyages[$id];
$_SESSION["voyage"]["name"]=$voyage["name"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($voyage['name']) ?> - Voyages Football</title>
  
  <!-- Lien vers le fichier CSS spécifique -->
  <link rel="stylesheet" href="../css/voyages.css">
  <script src="../js/navbar.js"></script>

  <!-- Importation de la police Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Entête navbar -->
  <?php include './header.php'; ?>
  
  <!-- Contenu principal -->
  <main>
    <div class="content-container">
      <!-- Affichage du titre, de l'image et de la description du voyage -->
      <section class="voyage-header">
        <h1><?= htmlspecialchars($voyage['name']) ?></h1>
        <img src="../photo/<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['name']) ?>">
        <p><?= htmlspecialchars($voyage['description']) ?></p>
        <?php if (isset($voyage['continent'])): ?>
          <p><strong>Continent :</strong> <?= htmlspecialchars($voyage['continent']) ?></p>
        <?php endif; ?>
      </section>
      
      <!-- Formulaire de personnalisation du voyage -->
      <form action="recap.php" method="post">
        <!-- Passage de l'identifiant du voyage dans un champ caché -->
        <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($id) ?>">
        
        <?php foreach ($voyage['etapes'] as $index => $etape): ?>
          <section class="etape">
            <h2>Étape <?= $index + 1 ?> : <?= htmlspecialchars($etape['titre']) ?></h2>
            <p><strong>Date :</strong></p>
            <?php 
              $date = $etape['date'];
              if ($date === '{{DATE_DYNAMIC}}'): 
                // Définir la date minimale sur la date du jour
                $minDate = date('Y-m-d');
            ?>
              <div class="date-choice">
                <label for="etape<?= $index ?>_date">Choisissez la date :</label>
                <input type="date" name="etapes[<?= $index ?>][date]" id="etape<?= $index ?>_date" min="<?= $minDate ?>">
              </div>
            <?php else:
              $dateObj = DateTime::createFromFormat('Y-m-d', $date);
              $errors = DateTime::getLastErrors();
              if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
                $date = "Date invalide";
              }
            ?>
              <input type="hidden" name="etapes[<?= $index ?>][date]" value="<?= htmlspecialchars($date) ?>">
            <?php endif; ?>
            <p><strong>Lieu :</strong> <?= htmlspecialchars($etape['lieu']) ?></p>
            <p><?= htmlspecialchars($etape['description']) ?></p>
            
            <!-- Options pour cette étape -->
            <?php foreach ($etape['options'] as $categorie => $options): ?>
              <div class="option-group">
                <label for="etape<?= $index ?>_<?= $categorie ?>"><?= ucfirst($categorie) ?> :</label>
                <?php if ($categorie === 'activites'): ?>
                  <fieldset>
                    <legend>Choisissez vos activités :</legend>
                    <?php foreach ($options as $option): ?>
                      <label>
                        <input type="checkbox" name="etapes[<?= $index ?>][<?= $categorie ?>][]" value="<?= htmlspecialchars($option['name']) ?>">
                        <?= htmlspecialchars($option['name']) ?> (<?= htmlspecialchars($option['price']) ?> €)
                      </label><br>
                    <?php endforeach; ?>
                  </fieldset>
                <?php else: ?>
                  <select name="etapes[<?= $index ?>][<?= $categorie ?>]" id="etape<?= $index ?>_<?= $categorie ?>">
                    <?php foreach ($options as $option): ?>
                      <option value="<?= htmlspecialchars($option['name']) ?>">
                        <?= htmlspecialchars($option['name']) ?> (<?= htmlspecialchars($option['price']) ?> €)
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </section>
        <?php endforeach; ?>
        
        <!-- Bouton de validation du formulaire -->
        <div class="submit-container">
          <button type="submit">Valider mon voyage</button>
        </div>
      </form>
    </div>
  </main>
  
  <!-- FOOTER -->
  <?php include './footer.php'; ?>
</body>
</html>
