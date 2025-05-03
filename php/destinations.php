<?php 
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('Location:./connexion.php');
    }

    if (isset($_SESSION['user_id'])) {
        echo '<script>console.log("Utilisateur connecté : ' . $_SESSION['user_id'] . '");</script>';
    } 
// Charger les destinations depuis le fichier JSON avec vérification d'erreur
$jsonPath = '../data/destinations.json';
$jsonData = file_get_contents($jsonPath);

if ($jsonData === false) {
  die("Erreur lors du chargement du fichier JSON.");
}

$destinations = json_decode($jsonData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
  die("Erreur lors du décodage JSON: " . json_last_error_msg());
}

unset($_SESSION['transaction_status']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stade Trotter - Voyages Football</title>
  
  <!-- Lien vers les fichiers CSS et JS -->
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
          <div class="search-container">
            <span class="icon">🔍</span>
            <input type="text" id="searchInput" class="search" placeholder="Rechercher...">
            <button type="button" id="searchButton" class="submit">Search</button>
          </div>
        </div>
      </section>


      <!-- FILTRE À GAUCHE -->
      <aside class="filter-section">
        <h2>Filtrer par</h2>
        <div class="filter-form">
          <div class="filter-group">
            <label for="pays">Pays</label>
            <select id="pays" name="pays">
              <option value="">-- TOUT --</option>
              <option value="france">France</option>
              <option value="allemagne">Allemagne</option>
              <option value="espagne">Espagne</option>
              <option value="angleterre">Angleterre</option>
              <option value="italie">Italie</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="capacity">Capacité</label>
            <select id="capacity" name="capacity">
              <option value="">-- TOUT --</option>
              <option value="moins-de-20000">Moins de 20,000</option>
              <option value="20000-50000">20,000 - 50,000</option>
              <option value="plus-de-50000">Plus de 50,000</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="type">Type de stade</label>
            <select id="type" name="type">
              <option value="">-- TOUT --</option>
              <option value="football">Football</option>
              <option value="multi-sport">Multi-sport</option>
              <option value="olympique">Olympique</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="roof">Toit</label>
            <select id="roof" name="roof">
              <option value="">-- TOUT --</option>
              <option value="ouvert">Ouvert</option>
              <option value="ferme">Fermé</option>
              <option value="retractable">Rétractable</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="year">Année de construction</label>
            <select id="year" name="year">
              <option value="">-- TOUT --</option>
              <option value="avant-1950">Avant 1950</option>
              <option value="1950-2000">1950 - 2000</option>
              <option value="apres-2000">Après 2000</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="price">Prix</label>
            <select id="price" name="price">
              <option value="">-- TOUT --</option>
              <option value="moins-de-1000">Moins de 1000€</option>
              <option value="1000-1500">1000€ - 1500€</option>
              <option value="plus-de-1500">Plus de 1500€</option>
            </select>
          </div>

          <button type="button" id="filterButton" class="submit">Filtrer</button>
        </div>
      </aside>

      <!-- LISTE DES STADES -->
      <section class="stadium-list" id="stadium-list">
        <?php foreach ($destinations as $key => $destination): ?>
          <a href="voyage.php?destination=<?= $key ?>" class="stadium-item" 
             data-continent="<?= htmlspecialchars(strtolower($destination['continent'] ?? '')) ?>" 
             data-pays="<?= htmlspecialchars(strtolower($destination['pays'] ?? '')) ?>" 
             data-capacity="<?= htmlspecialchars($destination['capacity'] ?? '') ?>" 
             data-type="<?= htmlspecialchars(strtolower($destination['typeFilter'] ?? '')) ?>" 
             data-roof="<?= htmlspecialchars(strtolower($destination['roofFilter'] ?? '')) ?>" 
             data-year="<?= htmlspecialchars($destination['yearOfConstruction'] ?? '') ?>"
             data-price="<?= htmlspecialchars($destination['travelprice'] ?? '') ?>"
             data-name="<?= htmlspecialchars(strtolower($destination['name'] ?? '')) ?>"
             data-description="<?= htmlspecialchars(strtolower($destination['description'] ?? '')) ?>">
            <div class="stadium">
              <img src="../photo/<?= htmlspecialchars($destination['image']) ?>" alt="<?= htmlspecialchars($destination['name']) ?>">
              <h2><?= htmlspecialchars($destination['name']) ?></h2>
              <p><?= htmlspecialchars($destination['description']) ?></p>
              <?php if (isset($destination['travelprice'])): ?>
                <p>Prix : <?= htmlspecialchars($destination['travelprice']) ?>€</p>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </section>
    </div>
  </main>

  <!-- FOOTER -->
  <?php include './footer.php'; ?>
  
  <!-- Script de filtrage -->
  <script src="../js/filtre.js"></script>
</body>
</html>