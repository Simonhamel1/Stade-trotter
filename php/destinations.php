
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stade Trotter - Voyages Football</title>
  
  <!-- Lien vers le fichier CSS -->
  <link rel="stylesheet" href="../css/destinations.css">
  <link rel="stylesheet" href="../chatbot/front/chatWidget.css">
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
        <form class="form">
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
      <form>
      <div class="filter-group">
        <label for="continent">Continent</label>
        <select id="continent" name="continent">
        <option value="europe">Europe</option>
        <option value="amerique">Amérique</option>
        <option value="asie">Asie</option>
        <option value="afrique">Afrique</option>
        <option value="oceanie">Océanie</option>
        </select>
      </div>
    
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

      <a href="stade-de-france.html">
        <div class="stadium">
          <img src="../photo/stade-de-france.jpg" alt="Stade de France">
          <h2>Stade de France</h2>
          <p>Plongez dans l'histoire du football français et vibrez au cœur de ce stade mythique.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/allianz-arena.jpg" alt="Allianz Arena">
          <h2>Allianz Arena</h2>
          <p>Découvrez le célèbre stade du Bayern Munich avec son architecture futuriste.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/camp-nou.jpeg" alt="Camp Nou">
          <h2>Camp Nou</h2>
          <p>Visitez la maison du FC Barcelone et ressentez l'ambiance unique de ce temple du football.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/wembley.jpeg" alt="Wembley Stadium">
          <h2>Wembley</h2>
          <p>Un lieu emblématique du football anglais où se jouent les plus grandes finales.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/san-siro.jpeg" alt="San Siro">
          <h2>San Siro</h2>
          <p>Explorez le stade légendaire de Milan, partagé par l'AC Milan et l'Inter Milan.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/bernabeu.jpg" alt="Santiago Bernabéu">
          <h2>Santiago Bernabéu</h2>
          <p>Visitez le stade emblématique du Real Madrid, un lieu chargé d'histoire et de gloire.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/anfield.jpeg" alt="Anfield">
          <h2>Anfield</h2>
          <p>Ressentez l'atmosphère unique du stade de Liverpool, célèbre pour son Kop.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/maracana.jpeg" alt="Maracanã">
          <h2>Maracanã</h2>
          <p>Découvrez le mythique stade de Rio de Janeiro, un symbole du football brésilien.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/signal-iduna-park.jpg" alt="Signal Iduna Park">
          <h2>Signal Iduna Park</h2>
          <p>Vivez l'ambiance électrique du stade du Borussia Dortmund, connu pour son Mur Jaune.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/azteca.jpg" alt="Estadio Azteca">
          <h2>Estadio Azteca</h2>
          <p>Explorez le stade de Mexico, célèbre pour avoir accueilli deux finales de Coupe du Monde.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/old-trafford.jpeg" alt="Old Trafford">
          <h2>Old Trafford</h2>
          <p>Visitez le stade mythique de Manchester United, un lieu chargé d'histoire et de légendes.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/orange-velodrome.jpeg" alt="Stade Vélodrome">
          <h2>Stade Vélodrome</h2>
          <p>Découvrez l'ambiance vibrante du Stade Vélodrome à Marseille.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/stade-olimpico.jpg" alt="Stadio Olimpico">
          <h2>Stadio Olimpico</h2>
          <p>Vivez l'expérience authentique du Stadio Olimpico à Rome.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/la-bombonera.jpeg" alt="La Bombonera">
          <h2>La Bombonera</h2>
          <p>Plongez au cœur de la passion argentine dans ce stade mythique.</p>
        </div>
      </a>

      <a href="">
        <div class="stadium">
          <img src="../photo/emirates-stadium.jpeg" alt="Emirates Stadium">
          <h2>Emirates Stadium</h2>
          <p>Explorez l'élégant Emirates Stadium, symbole du football londonien.</p>
        </div>
      </a>
      
    </section>
  </div>
  </main>

  <!-- FOOTER -->
  <?php include './footer.php'; ?>

</body>
</html>