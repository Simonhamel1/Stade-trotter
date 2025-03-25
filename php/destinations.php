<?php
  session_start();
// Charger les destinations depuis le fichier JSON
$jsonData = file_get_contents('../data/destinations.json');
$destinations = json_decode($jsonData, true);

// Récupérer et nettoyer les paramètres de l'URL
$continent = isset($_GET['continent']) ? trim($_GET['continent']) : '';
$pays      = isset($_GET['pays']) ? trim($_GET['pays']) : '';
$capacity  = isset($_GET['capacity']) ? trim($_GET['capacity']) : '';
$type      = isset($_GET['type']) ? trim($_GET['type']) : '';
$roof      = isset($_GET['roof']) ? trim($_GET['roof']) : '';
$year      = isset($_GET['year']) ? trim($_GET['year']) : '';
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';

// Filtrer les destinations selon tous les critères
$filtered_destinations = array_filter($destinations, function ($destination) use ($continent, $pays, $capacity, $type, $roof, $year, $search) {
  // Filtrer par recherche textuelle dans le nom ou la description
  if ($search) {
    $needle = strtolower($search);
    $name  = strtolower($destination['name'] ?? '');
    $desc  = strtolower($destination['description'] ?? '');
    if (strpos($name, $needle) === false && strpos($desc, $needle) === false) {
      return false;
    }
  }
  // Filtrer par continent (s'il est défini)
  if ($continent && strtolower($destination['continent'] ?? '') !== strtolower($continent)) {
    return false;
  }
  // Filtrer par pays
  if ($pays && strtolower($destination['pays'] ?? '') !== strtolower($pays)) {
    return false;
  }
  // Filtrer par capacité, en supposant que $destination['capacity'] est un nombre
  if ($capacity && isset($destination['capacity'])) {
    if ($capacity === 'moins-de-20000' && $destination['capacity'] >= 20000) {
      return false;
    }
    if ($capacity === '20000-50000' && ($destination['capacity'] < 20000 || $destination['capacity'] > 50000)) {
      return false;
    }
    if ($capacity === 'plus-de-50000' && $destination['capacity'] <= 50000) {
      return false;
    }
  }
  // Filtrer par type
  if ($type && strtolower($destination['type'] ?? '') !== strtolower($type)) {
    return false;
  }
  // Filtrer par toit
  if ($roof && strtolower($destination['roof'] ?? '') !== strtolower($roof)) {
    return false;
  }
  // Filtrer par année de construction
  if ($year && isset($destination['year'])) {
    if ($year === 'avant-1950' && $destination['year'] >= 1950) {
      return false;
    }
    if ($year === '1950-2000' && ($destination['year'] < 1950 || $destination['year'] > 2000)) {
      return false;
    }
    if ($year === 'apres-2000' && $destination['year'] <= 2000) {
      return false;
    }
  }
  return true;
});
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
        <!-- Champ de recherche -->
        <input type="text" name="search" class="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="submit">Search</button>
      </div>
      </form>
    </div>
    </section>
  
    <!-- FILTRE À GAUCHE -->
    <aside class="filter-section">
    <h2>Filtrer par</h2>
    <form method="get" action="">
      <!-- Garde les autres paramètres si nécessaire -->
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <input type="hidden" name="continent" value="<?= htmlspecialchars($continent) ?>">
    
      <div class="filter-group">
      <label for="pays">Pays</label>
      <select id="pays" name="pays">
        <option value="" <?= $pays === '' ? 'selected' : '' ?>>Tous les stades</option>
        <option value="france" <?= $pays === 'france' ? 'selected' : '' ?>>France</option>
        <option value="allemagne" <?= $pays === 'allemagne' ? 'selected' : '' ?>>Allemagne</option>
        <option value="espagne" <?= $pays === 'espagne' ? 'selected' : '' ?>>Espagne</option>
        <option value="angleterre" <?= $pays === 'angleterre' ? 'selected' : '' ?>>Angleterre</option>
        <option value="italie" <?= $pays === 'italie' ? 'selected' : '' ?>>Italie</option>
      </select>
      </div>
      
      <div class="filter-group">
      <label for="capacity">Capacité</label>
      <select id="capacity" name="capacity">
        <option value="" <?= $capacity === '' ? 'selected' : '' ?>>-- Choisir --</option>
        <option value="moins-de-20000" <?= $capacity === 'moins-de-20000' ? 'selected' : '' ?>>Moins de 20,000</option>
        <option value="20000-50000" <?= $capacity === '20000-50000' ? 'selected' : '' ?>>20,000 - 50,000</option>
        <option value="plus-de-50000" <?= $capacity === 'plus-de-50000' ? 'selected' : '' ?>>Plus de 50,000</option>
      </select>
      </div>
  
      <div class="filter-group">
      <label for="type">Type de stade</label>
      <select id="type" name="type">
        <option value="" <?= $type === '' ? 'selected' : '' ?>>-- Choisir --</option>
        <option value="football" <?= $type === 'football' ? 'selected' : '' ?>>Football</option>
        <option value="multi-sport" <?= $type === 'multi-sport' ? 'selected' : '' ?>>Multi-sport</option>
        <option value="olympique" <?= $type === 'olympique' ? 'selected' : '' ?>>Olympique</option>
      </select>
      </div>
  
      <div class="filter-group">
      <label for="roof">Toit</label>
      <select id="roof" name="roof">
        <option value="" <?= $roof === '' ? 'selected' : '' ?>>-- Choisir --</option>
        <option value="ouvert" <?= $roof === 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
        <option value="ferme" <?= $roof === 'ferme' ? 'selected' : '' ?>>Fermé</option>
        <option value="retractable" <?= $roof === 'retractable' ? 'selected' : '' ?>>Rétractable</option>
      </select>
      </div>
  
      <div class="filter-group">
      <label for="year">Année de construction</label>
      <select id="year" name="year">
        <option value="" <?= $year === '' ? 'selected' : '' ?>>-- Choisir --</option>
        <option value="avant-1950" <?= $year === 'avant-1950' ? 'selected' : '' ?>>Avant 1950</option>
        <option value="1950-2000" <?= $year === '1950-2000' ? 'selected' : '' ?>>1950 - 2000</option>
        <option value="apres-2000" <?= $year === 'apres-2000' ? 'selected' : '' ?>>Après 2000</option>
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
        <img src="../photo/<?= $destination['image'] ?>" alt="<?= htmlspecialchars($destination['name']) ?>">
        <h2><?= htmlspecialchars($destination['name']) ?></h2>
        <p><?= htmlspecialchars($destination['description']) ?></p>
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
