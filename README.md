# Virtual Trader

Virtual Trader est un jeu de simulation boursière réalisé en PHP et MySQL pour le projet IF2. On achète et on revend des actions avec un solde fictif, puis on retrouve l’évolution de son portefeuille et sa place au classement.

Le jeu avance par tours. Chaque tour représente un mois et met à jour les cours et les dividendes. On peut aussi emprunter et suivre d’autres joueurs.

## Essayer le jeu en local

1. Démarre Apache et MySQL dans XAMPP, puis place le dossier du projet dans `htdocs/virtual-trader`.
2. Dans phpMyAdmin, crée la base `virtual_trader` et importe [virtual_trader.sql](virtual_trader.sql).
3. Ouvre `http://localhost/virtual-trader/public/` et crée un compte de test.

Par défaut, PHP se connecte à MySQL sur `localhost` avec l’utilisateur `root` et un mot de passe vide, comme sur une installation locale de XAMPP. Si ta configuration diffère, renseigne `DB_HOST`, `DB_DBNAME`, `DB_USERNAME` et `DB_PASSWORD` dans l’environnement utilisé par PHP.

Pour faire avancer le marché automatiquement, lance cette commande depuis la racine du projet et laisse le terminal ouvert. L’argument indique l’intervalle en secondes.

```sh
php auto_simulate_windows_fix.php 60
```

Le projet est prévu pour une démonstration locale. L’attribution du rôle administrateur repose sur l’adresse e-mail saisie à l’inscription.

Projet réalisé en équipe par **Elio, Bilel et Eliott**. [Rapport du projet](rapport.pdf)
