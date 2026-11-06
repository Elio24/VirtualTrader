<?php
// Définir la racine du projet basée sur l'emplacement de ce fichier (templates)
// Utilisation de DIRECTORY_SEPARATOR pour la compatibilité
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Virtual Trader</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            padding-top: 60px; /* Espace pour la barre de navigation et le menu utilisateur fixés */
        }

        .header-container {
            position: fixed; /* Fixer en haut de la page */
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* S'assurer qu'il est au-dessus du contenu de la page */
            background-color: #337ab7; /* Couleur de fond pour toute la zone du header */
        }

        nav {
            background-color: #337ab7 !important; /* Couleur de fond de la nav principale */
            padding: 5px 0; 
            overflow: visible;
            display: flex; /* Utiliser flex pour aligner les éléments */
            justify-content: space-between; /* Espacer les liens nav et le menu utilisateur */
            align-items: center; 
            padding-left: 15px; 
            padding-right: 15px; 
        }

        .main-nav-links {

            background-color: transparent;
        }

        nav a.main-nav-item {

            display: inline-block; 
            color: white; 
            text-align: center;
            padding: 10px 12px; 
            text-decoration: none;
            font-size: 15px; 
            background-color: transparent; 
        }

        nav a.main-nav-item:hover {
            background-color: #286090; /* Fond bleu foncé au survol */
            color: white; /* S'assurer que le texte reste blanc */
        }

        /* Styles pour le menu déroulant utilisateur */
        .user-menu {
            position: relative; 
            display: inline-block;
        }

        .user-menu-trigger {
            background-color: #4A90E2; /* Couleur de fond du bouton */
            color: white;
            padding: 8px 12px; /* Ajusté pour l'icône image */
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            display: flex; /* Pour aligner image et texte */
            align-items: center; /* Centrer verticalement image et texte */
        }

        .user-menu-trigger img {
            width: 24px; /* Taille de l'icône */
            height: 24px;
            border-radius: 50%; /* Rendre l'icône circulaire */
            margin-right: 8px; /* Espace entre l'icône et le texte */
        }

        .user-menu-trigger:hover {
            background-color: #357ABD; 
        }

        .user-menu-content {
            display: none; 
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px; /* Largeur minimale du menu */
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1001; /* z-index élevé pour être au-dessus de la nav */
            right: 0; /* Aligner le menu déroulant avec le bord droit du bouton */
            top: 100%; /* Positionner juste en dessous du bouton déclencheur */
            border-radius: 5px;
        }

        .user-menu-content.show {
            display: block; 
        }

        .user-menu-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #eee;
        }

        .user-menu-content a:last-child {
            border-bottom: none;
        }

        .user-menu-content a:hover {background-color: #f1f1f1}

    </style>
    <?php if (is_logged_in()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuTrigger = document.querySelector('.user-menu-trigger');
            const userMenuContent = document.querySelector('.user-menu-content');

            if (userMenuTrigger && userMenuContent) {
                userMenuTrigger.addEventListener('click', function(event) {
                    event.stopPropagation(); 
                    userMenuContent.classList.toggle('show');
                });

                // Fermer le menu si on clique en dehors
                window.addEventListener('click', function(event) {
                    if (userMenuContent.classList.contains('show') && !userMenuTrigger.contains(event.target) && !userMenuContent.contains(event.target)) {
                        userMenuContent.classList.remove('show');
                    }
                });
            }
        });
    </script>
    <?php endif; ?>
</head>
<body>
    <?php if (is_logged_in()): ?>
        <div class="header-container"> 
            <nav>
                <div class="main-nav-links">
                    <a href="dashboard.php" class="main-nav-item">Tableau de bord</a>
                    <a href="buy_sell.php" class="main-nav-item">Acheter/Vendre</a>
                    <a href="rechercher_actions.php" class="main-nav-item">Rechercher</a>
                    <a href="classement.php" class="main-nav-item">Classement</a>
                    <a href="dividendes.php" class="main-nav-item">Dividendes</a>
                    <a href="prix_actions.php" class="main-nav-item">Graphiques actions</a>
                </div>

                <div class="user-menu">
                    <button class="user-menu-trigger">
                        <img src="images/spongebob_icon.png" alt="Icône Utilisateur">
                        Mon Compte
                    </button>
                    <div class="user-menu-content">
                        <a href="suivis.php">Joueurs Suivis</a>
                        <a href="emprunt.php">Emprunter</a>
                        <a href="historique_portefeuille.php">Historique du portefeuille</a>
                        <a href="modifier_mdp.php">Modifier mot de passe</a>
                        <a href="logout.php">Se déconnecter</a>
                    </div>
                </div>
            </nav>
        </div>
        <hr style="clear:both; display:none;"> 
    <?php endif; ?>



