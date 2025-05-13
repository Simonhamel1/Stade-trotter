<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stade Trotter - Voyages Football</title>
  
  <!-- Lien vers le fichier CSS -->
  <link rel="stylesheet" href="../css/a_propos.css">
  <script src="../js/navbar.js" defer></script>

  <!-- Importation de la police Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- HEADER FIXE -->
  <header>
    <?php include '../php/header.php'; ?>
  </header>
  <body>
    <section id="presentation">
        <div class="container1">
            <h1>Présentation du Projet</h1>
            <p>Bienvenue sur <strong>Stade Trotter</strong>, le site qui vous emmène découvrir les plus grands stades du monde… même si, soyons honnêtes, on ne sait pas vraiment pourquoi on a choisi ce thème, vu qu'on n'aime pas le foot !</p>
            <p>Peut-être est-ce la magie des tribunes, l’ambiance enflammée des supporters, ou juste une mauvaise décision en fin de soirée... Quoi qu'il en soit, nous avons créé ce projet avec passion (ou du moins beaucoup d'efforts) pour vous offrir une expérience unique de voyage autour du football.</p>
            <p>Imaginez-vous en train de visiter des stades légendaires, de sentir l'odeur de l'herbe fraîchement coupée, et d'entendre les chants des supporters résonner dans les gradins. Bon, en réalité, nous préférons probablement être devant nos écrans à coder, mais nous avons mis tout notre cœur (et notre humour) dans ce projet.</p>
            <p>Alors, que vous soyez un fan inconditionnel de football ou simplement curieux de découvrir ce monde fascinant, nous espérons que vous apprécierez votre visite sur notre site. Et si jamais vous vous demandez pourquoi deux geeks ont décidé de créer un site sur les stades de football, sachez que nous nous posons encore la question nous-mêmes !</p>

            <h2>À propos des créateurs</h2>
            <div class="creators">
                <div class="creator">
                    <a href="https://www.linkedin.com/in/simon-hamelin" target="_blank"><img src="../photo/simon.jpg" alt="linkedin simon"></a>
                    <h3>Simon Hamelin</h3>
                    <p>Simon est étudiant en informatique et développeur web. Il aime coder, construire des interfaces fluides… mais le football ? Pas vraiment son truc. Pourtant, il s’est retrouvé à coder un site dédié aux voyages de stades. Allez comprendre.</p>
                </div>
                <div class="creator">
                    <a href="https://www.linkedin.com/in/ewan-clabaut-116134283/?originalSubdomain=fr" target="_blank">
                      <img src="../photo/ewan.jpg" alt="Ewan Clabaut">
                    </a>
                    <h3>Ewan Clabaut</h3>
                    <p>Ewan, lui aussi étudiant en informatique, adore concevoir des architectures logicielles solides. Fan de sport en général, mais pas forcément de foot, il a quand même mis toute son expertise pour que ce projet tienne la route. Une ironie du destin ? Peut-être.</p>
                </div>
            </div>
        </div>
    </section>
  </body>
   

  <!-- FOOTER -->
  <?php include '../php/footer.php'; ?> 


</body>
</html>