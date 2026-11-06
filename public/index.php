<?php
require_once '../includes/functions.php';
require_once '../templates/header.php';
?>

<div class="section-entete">
    <div class="conteneur">
        <div class="contenu-entete">
            <h1 class="titre-entete"><span class="surbrillance">Virtual Trader</span> <span class="texte-anime">Votre Passerelle vers le Trading</span></h1>
            <p class="sous-titre-entete">Simulez vos investissements en bourse avec 10 000 € virtuels, suivez vos actions en temps réel, et développez vos compétences sans risque financier.</p>
            <div class="boutons-action">
                <a href="login.php" class="bouton-principal">Se connecter</a>
                <a href="register.php" class="bouton-secondaire">Créer un compte</a>
            </div>
        </div>
        <div class="graphique-entete">
            <div class="conteneur-graphique">
                <img src="images/image1.jpg" alt="Trading" class="image-graphique">
            </div>
        </div>
    </div>
    <div class="separateur-simple"></div>
</div>

<div class="section-fonctionnement">
    <div class="conteneur">
        <h2 class="titre-section">Comment ça marche</h2>
        <div class="conteneur-etapes">
            <div class="etape">
                <div class="icone-etape">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>1. Inscrivez-vous</h3>
                <p>Créez votre compte gratuitement en quelques secondes et accédez à votre portefeuille virtuel.</p>
            </div>
            <div class="etape">
                <div class="icone-etape">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>2. Investissez</h3>
                <p>Utilisez vos 10 000 € virtuels pour acheter et vendre des actions sur les marchés en temps réel.</p>
            </div>
            <div class="etape">
                <div class="icone-etape">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>3. Progressez</h3>
                <p>Analysez vos performances, affinez vos stratégies et devenez un trader expérimenté sans risque.</p>
            </div>
        </div>
    </div>
</div>

<div class="section-fonctionnalites">
    <div class="conteneur">
        <h2 class="titre-section">Fonctionnalités principales</h2>
        <div class="grille-fonctionnalites">
            <div class="carte-fonctionnalite">
                <div class="icone-fonctionnalite">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>10 000 € Virtuels</h3>
                <p>Commencez avec un capital virtuel conséquent pour explorer différentes stratégies d'investissement.</p>
            </div>
            <div class="carte-fonctionnalite">
                <div class="icone-fonctionnalite">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Données en Temps Réel</h3>
                <p>Accédez aux cours boursiers actualisés pour prendre des décisions éclairées.</p>
            </div>
            <div class="carte-fonctionnalite">
                <div class="icone-fonctionnalite">
                    <i class="fas fa-history"></i>
                </div>
                <h3>Historique Détaillé</h3>
                <p>Suivez l'évolution de votre portefeuille et analysez vos transactions passées.</p>
            </div>
            <div class="carte-fonctionnalite">
                <div class="icone-fonctionnalite">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Sans Risque Financier</h3>
                <p>Apprenez et expérimentez sans craindre de perdre votre argent réel.</p>
            </div>
        </div>
    </div>
</div>

<div class="section-temoignages">
    <div class="conteneur">
        <h2 class="titre-section">Ce que disent nos utilisateurs</h2>
        <div class="conteneur-temoignages">
            <div class="carte-temoignage">
                <div class="evaluation-temoignage">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="texte-temoignage">"Virtual Trader m'a permis d'apprendre les bases du trading sans risquer mon épargne. Une plateforme idéale pour les débutants !"</p>
                <div class="auteur-temoignage">
                    <div class="avatar-temoignage">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <p>Sophie L.</p>
                </div>
            </div>
            <div class="carte-temoignage">
                <div class="evaluation-temoignage">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="texte-temoignage">"Interface intuitive et données fiables. J'utilise Virtual Trader pour tester mes stratégies avant de les appliquer sur le marché réel."</p>
                <div class="auteur-temoignage">
                    <div class="avatar-temoignage">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <p>Bilel caca</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-appel-action">
    <div class="conteneur">
        <h2 class="titre-section">Prêt à devenir un trader accompli ?</h2>
        <p class="texte-appel-action">Rejoignez des milliers d'utilisateurs qui développent leurs compétences en trading sans risque financier.</p>
        <div class="boutons-action">
            <a href="register.php" class="bouton-principal">Commencer gratuitement</a>
            <a href="login.php" class="bouton-secondaire">Se connecter</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="js/animations.js"></script>

<?php require_once '../templates/footer.php'; ?>
