# README - Projet Virtual Trader

## Introduction

Virtual Trader est une application web de simulation de trading d'actions développée en PHP et MySQL. Ce projet a été réalisé par Eliott, Elio et Bilel.
Ce fichier README explique comment installer et configurer et utiliser l'application.

## Prérequis

   Un serveur web local (nous avons utiliser Xamp) avec Apache et MySQL.
   PHP
   Un navigateur web 
   phpMyAdmin 

## Installation

1.  **Télécharger les fichiers du projet :**
    *   Assurez-vous d'avoir le dossier complet du projet `virtual_trader-test`.

2.  **Placer les fichiers sur le serveur web :**
    *   Copiez le dossier `virtual_trader-test` dans le répertoire `htdocs` (pour XAMPP) de votre serveur web local.
    *   Par exemple : `C:\xampp\htdocs\virtual_trader-test`

3.  **Créer la base de données :**
    *   Ouvrez phpMyAdmin (accessible via `http://localhost/phpmyadmin`).
    *   Créez une nouvelle base de données nommée `virtual_trader` (utilisez l'encodage `utf8mb4_general_ci`).
    *   Sélectionnez la base de données `virtual_trader` que vous venez de créer.
    *   Allez dans l'onglet "Importer".
    *   Cliquez sur "Choisir un fichier" et sélectionnez le fichier `virtual_trader.sql` fourni avec le projet.
    *   Cliquez sur "Exécuter" pour importer la structure des tables et les données initiales.

4.  **Configurer la connexion à la base de données :**
    *   Ouvrez le fichier `virtual_trader-test/config/database.php` dans un éditeur de texte.
    *   Vérifiez et ajustez si nécessaire les informations de connexion à votre base de données MySQL :
        ```php
        $host = 'localhost'; // Ou l'hôte de votre serveur MySQL
        $db   = 'virtual_trader';
        $user = 'root';      // Votre nom d'utilisateur MySQL (souvent 'root' par défaut sur XAMPP)
        $pass = '';          // Votre mot de passe MySQL (souvent vide par défaut sur XAMPP)
        $charset = 'utf8mb4';
        ```
    *   Enregistrez les modifications.

5.  **Accéder à l'application :**
    *   Ouvrez un navigateur et accédez à l'URL correspondante à l'emplacement du projet.`http://localhost/virtual_trader-test/public/`

## Utilisation

### Comptes utilisateurs

   **Inscription :** Vous pouvez créer un nouveau compte utilisateur via la page d'inscription.
   **Connexion :** Utilisez vos identifiants pour vous connecter.

   **Compte Administrateur :**
    *   Pour créer un compte avec des droits d'administrateur, assurez-vous que l'adresse e-mail utilisée lors de l'inscription contienne le mot "admin" (par exemple, `admin@gmail.com`).
    *   Un compte administrateur a accès à des fonctionnalités supplémentaires, notamment la possibilité de réinitialiser l'ensemble du jeu via le script `reset_complet_avec_utilisateurs.php` (accessible directement par URL si l'administrateur est connecté ou sur a page dashboard avec un gros bouton rouge).

   **Utilisateur de test :**
    *   Vous pouvez créer un utilisateur de test en suivant la procédure d'inscription. Par exemple :
        *   Email : `test@example.com`
        *   Mot de passe : `password123`
    *   Ou un utilisateur admin de test :
        *   Email : `testadmin@example.com`
        *   Mot de passe : `password123`

### Fonctionnalités principales

Une fois connecté, vous pourrez :

   Consulter votre **Tableau de bord** avec vos liquidités et la valeur de votre portefeuille.
   **Acheter/Vendre** des actions.
   Consulter l'**Historique de votre portefeuille** et l'**évolution des prix des actions**.
   Voir le **Classement** des joueurs.
   Gérer les **Dividendes** et les **Emprunts**.
   **Suivre** d'autres joueurs.
   Modifier votre **mot de passe**.

### Simulation du temps et mise à jour du marché (Changement d'heure automatique)

Le jeu simule le passage du temps (un mois par tour) pour mettre à jour les prix des actions et distribuer les dividendes. Cette simulation est gérée par le script `auto_simulate_windows_fix.php`.

**Pour activer la simulation automatique (par exemple, sur XAMPP sous Windows) :**

1.  **Ouvrez le shell XAMPP :**
       Lancez le Panneau de Contrôle XAMPP.
       Cliquez sur le bouton "Shell" à côté de la ligne Apache ou MySQL.

2.  **Naviguez vers le dossier du projet :**
    *   Dans le terminal qui s'ouvre, tapez la commande suivante (adaptez le chemin si nécessaire) :
        
        cd C:\xampp\htdocs\virtual_trader-test
        
3.  **Lancez le script de simulation :**
       Vous pouvez lancer le script pour qu'il s'exécute une fois et simule un passage de temps (par défaut, le script est configuré pour une durée d'exécution qui correspond à un cycle de jeu, par exemple 60 secondes pour simuler un mois) :
        
        php auto_simulate_windows_fix.php

       Vous pouvez spécifier une durée en secondes. Par exemple, pour 5 minutes (300 secondes) sinon a durée de base est de 60s soit 1minute :
        
        php auto_simulate_windows_fix.php 300

## Auteurs

   Elio
   Bilel
   Eliott

Nous espérons que vous apprécierez notre projet Virtual Trader !
