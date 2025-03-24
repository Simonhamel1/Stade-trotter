<?php
// Charger les destinations depuis le fichier JSON
$jsonData = file_get_contents('../data/destinations.json');
$destinations = json_decode($jsonData, true);

// Récupérer et nettoyer le paramètre de filtrage du continent depuis l'URL
$continent = isset($_GET['continent']) ? trim($_GET['continent']) : '';

// Filtrer par continent (comparaison insensible à la casse)
if ($continent) {
  $filtered_destinations = array_filter($destinations, function($destination) use ($continent) {
    return strtolower($destination['continent']) === strtolower($continent);
  });
} else {
  $filtered_destinations = $destinations;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stade Trotter - Voyages Football</title>
  
  <!-- Lien vers le fichier CSS -->
  <link rel="stylesheet" href="../css/destinations.css">
  <script src="../js/navbar.js"></script>

  <!-- Importation de la police Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Entête navbar -->
  <?php include './header.php'; ?>
  
  <!-- SECTION HERO -->
  <main>
    <div class="double-grid">
      <!-- RECHERCHE EN HAUT -->
      <section class="hero">
        <div class="hero-content">
          <h1>Voyagez à travers les plus grands stades du monde !</h1>
          <p>Découvrez des destinations inoubliables pour les passionnés de football.</p>
          <form class="form" action="" method="get">
            <div class="search-container">
              <span class="icon">🔍</span>
              <input type="text" class="search" placeholder="Rechercher...">
              <button type="submit" class="submit">Search</button>
            </div>
          </form>
        </div>
      </section>
    
      <!-- FILTRE À GAUCHE -->
      <aside class="filter-section">
        <h2>Filtrer par</h2>
        <form method="get" action="">
          <div class="filter-group">
            <label for="continent">Continent</label>
            <select id="continent" name="continent">
              <option value="" <?= $continent === '' ? 'selected' : '' ?>>Tous</option>
              <option value="europe" <?= $continent == 'europe' ? 'selected' : '' ?>>Europe</option>
              <option value="amerique" <?= $continent == 'amerique' ? 'selected' : '' ?>>Amérique</option>
              <option value="asie" <?= $continent == 'asie' ? 'selected' : '' ?>>Asie</option>
              <option value="afrique" <?= $continent == 'afrique' ? 'selected' : '' ?>>Afrique</option>
              <option value="oceanie" <?= $continent == 'oceanie' ? 'selected' : '' ?>>Océanie</option>
            </select>
          </div>
          
          <!-- Autres filtres (non traités côté PHP, à adapter au besoin) -->
          <div class="filter-group">
            <label for="capacity">Capacité</label>
            <select id="capacity" name="capacity">
              <option value="moins-de-20000">Moins de 20,000</option>
              <option value="20000-50000">20,000 - 50,000</option>
              <option value="plus-de-50000">Plus de 50,000</option>
            </select>
          </div>
    
          <div class="filter-group">
            <label for="type">Type de stade</label>
            <select id="type" name="type">
              <option value="football">Football</option>
              <option value="multi-sport">Multi-sport</option>
              <option value="olympique">Olympique</option>
            </select>
          </div>
    
          <div class="filter-group">
            <label for="roof">Toit</label>
            <select id="roof" name="roof">
              <option value="ouvert">Ouvert</option>
              <option value="ferme">Fermé</option>
              <option value="retractable">Rétractable</option>
            </select>
          </div>
    
          <div class="filter-group">
            <label for="year">Année de construction</label>
            <select id="year" name="year">
              <option value="avant-1950">Avant 1950</option>
              <option value="1950-2000">1950 - 2000</option>
              <option value="apres-2000">Après 2000</option>
            </select>
          </div>
        
          <button type="submit" class="submit">Filtrer</button>
        </form>
      </aside>
    
      <!-- LISTE DES STADES -->
      <section class="stadium-list">
        <?php foreach ($filtered_destinations as $key => $destination): ?>
          <a href="voyage.php?destination=<?= $key ?>">
            <div class="stadium">
              <img src="../photo/<?= $destination['image'] ?>" alt="<?= $destination['name'] ?>">
              <h2><?= $destination['name'] ?></h2>
              <p><?= $destination['description'] ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </section>
    </div>
  </main>

  <!-- FOOTER -->
  <?php include './footer.php'; ?>

</body>
</html>
