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
$_SESSION['voyage_id'] = $_GET['destination'];
// Récupérer l'identifiant du voyage depuis l'URL
$id = $_SESSION['voyage_id'] ?? '';
if (!$id || !isset($voyages[$id])) {
  echo "<p>Voyage non trouvé. <a href='destinations.php'>Retour aux destinations</a></p>";
  exit();
}
$voyage = $voyages[$id];
$_SESSION["voyage"]["name"] = $voyage["name"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($voyage['name']) ?> - Voyages Football</title>
  <link rel="stylesheet" href="../css/voyages.css">
  <script src="../js/navbar.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
  <?php include './header.php'; ?>
  
  <main>
    <div class="content-container">
      <section class="voyage-header">
        <h1><?= htmlspecialchars($voyage['title'] ?? $voyage['name']) ?></h1>
        <img src="../photo/<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['name']) ?>">
        <p><?= htmlspecialchars($voyage['description']) ?></p>
        
        <div class="voyage-details">
          <?php if (isset($voyage['continent'])): ?>
            <p><strong>Continent :</strong> <?= htmlspecialchars($voyage['continent']) ?></p>
          <?php endif; ?>
          
          <?php if (isset($voyage['prix'])): ?>
            <p><strong>Prix estimé par personne :</strong> <?= htmlspecialchars($voyage['prix']) ?> €</p>
            <!-- Le prix total reste statique -->
            <p id="totalPrice"><strong>Prix total :</strong> <?= htmlspecialchars($voyage['prix']) ?> €</p>
          <?php endif; ?>
        </div>
      </section>
      
      <form action="recap.php" method="post">
        <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($id) ?>">
        
        <!-- Section des dates -->
        <section class="date-selection">
          <h2>Dates du voyage</h2>
          <div class="date-input">
            <label for="date_depart">Date de départ :</label>
            <input type="date" id="date_depart" name="date_depart" required>
            
            <label for="date_retour">Date de retour :</label>
            <input type="date" id="date_retour" name="date_retour" required>
          </div>
        </section>
        
        <!-- Section des participants simplifiée -->
        <section class="participants">
          <div class="participant-count">
            <label for="nb_participants">Nombre de participants :</label>
            <input type="number" id="nb_participants" name="nb_participants" min="1" max="8" value="1" required>
          </div>
        </section>

        <?php if (isset($voyage['etapes']) && is_array($voyage['etapes'])): ?>
          <?php foreach ($voyage['etapes'] as $index => $etape): ?>
          <section class="etape">
            <h2>Étape <?= $index + 1 ?> : <?= htmlspecialchars($etape['titre']) ?></h2>
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
                        <input type="checkbox" name="etapes[<?= $index ?>][<?= $categorie ?>][]" value="<?= htmlspecialchars($option['name']) ?>"
                          <?= (isset($option['default']) && $option['default']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($option['name']) ?> (<?= htmlspecialchars($option['price']) ?> €)
                      </label>
                    <?php endforeach; ?>
                  </fieldset>
                <?php else: ?>
                  <select name="etapes[<?= $index ?>][<?= $categorie ?>]" id="etape<?= $index ?>_<?= $categorie ?>">
                    <?php foreach ($options as $option): ?>
                      <option value="<?= htmlspecialchars($option['name']) ?>" 
                        <?= (isset($option['default']) && $option['default']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($option['name']) ?> (<?= htmlspecialchars($option['price']) ?> €)
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </section>
        <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="submit-container">
          <button type="submit">Valider mon voyage</button>
        </div>
      </form>
    </div>
  </main>
  
  <?php include './footer.php'; ?>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Définir la date minimum (aujourd'hui) pour les champs de date
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('date_depart').min = today;
      document.getElementById('date_retour').min = today;
      
      // Validation: la date de retour doit être après la date de départ
      document.getElementById('date_depart').addEventListener('change', function() {
        document.getElementById('date_retour').min = this.value;
      });
      
    });
  </script>
</body>
</html>
