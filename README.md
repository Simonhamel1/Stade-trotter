# Stade Trotter

Bienvenue sur le projet **Stade Trotter**.

## Description

L’objectif est de créer un site web pour une agence de voyages proposant des séjours configurés par étapes. Le client peut choisir un circuit prédéfini avec des lieux fixes, mais personnaliser l’hébergement, la restauration, les activités et le transport. Ce concept de séjour « pseudo-sur-mesure » combine l’efficacité d’un circuit standardisé avec la flexibilité des options individuelles. Le site permettra aux utilisateurs de s’inscrire, se connecter, modifier leurs options de voyage et procéder à l’achat via une phase de paiement intégrée. Le développement se fera par étapes, correspondant aux chapitres du cours d’Informatique 4, et sera évalué tout au long du semestre.

## Fonctionnalités

- page presentes :
    - Une page d’accueil par défaut avec la présentatio du site (acceuil.html)
    - Une page pour la présentation du site qui reprend votre nom et votre thème (a_propos.html)
    - Une page spécifique pour rechercher des voyages avec plusieurs champs de filtrage (dates, lieux, options, ...) + champ de recherche (destination.html)
    - Une page avec un formulaire pour s’inscrire (inscription.html)
    - Une page avec un formulaire pour se connecter lorsque l’on est déjà inscrit sur le site (connexion.html)
    - Une page pour afficher le propre profil d’un utilisateur connecté avec des boutons pour modifier les différents champs (bouton ‘modifier’ ou avec un icône ‘crayon’, ...) (profil.html)
    - Un page de type ‘administrateur’ avec une liste d’utilisateurs inscrits et des boutons permettant de modifier une propriété de chaque utilisateur (ex : client VIP qui aurait une réduction des prix, bannissement du client qui ne pourrait plus acheter de voyages, …) (admin.html)

## Installation

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/votre-utilisateur/stade-trotter.git
    ```
2. Accédez au répertoire du projet :
    ```bash
    cd stade-trotter
    ```
3. Installer python et flask (pour le chatbot qui ne fonctionne pas ppour l'instant donc pas necessaire) :
    ```bash
    sudo apt-get install python3 python3-pip
    pip install Flask
    ```

## Utilisation

1. Démarrez le serveur de développement :
    ```bash
    npm start
    ```

2. Accédez au répertoire du projet :
    ```bash
    cd chatbot
    ```

3. Lancez le programme principal :
    ```bash
    python main.py
    ``` 

4. Ouvrez votre navigateur et accédez à `http://localhost:3000`.

## Contributeur
[Simon](https://github.com/Simonhamel1) et [Ewan](https://github.com/Clab-ewan).
