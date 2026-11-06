<?php
require_once '../config/database.php';

// Suppression complète des données
$pdo->exec("DELETE FROM POSSESSION_ACTION");
$pdo->exec("DELETE FROM TRANSACTION");
$pdo->exec("DELETE FROM HISTORIQUE_PORTEFEUILLE");
$pdo->exec("DELETE FROM HISTORIQUE_PRIX");
$pdo->exec("DELETE FROM DIVIDENDE_RECU");
$pdo->exec("DELETE FROM UTILISATEUR");

// Réinitialisation des actions
$pdo->exec("UPDATE ACTION SET 
    prix_actuel = 100.00, 
    evolution_precedente = 0, 
    date_dividende = NULL, 
    montant_dividende = 0
");

echo "<h3> Projet complètement remis à zéro !</h3>";
echo "<ul>
    <li>Tous les utilisateurs supprimés</li>
    <li>Toutes les transactions, possessions, historiques et dividendes supprimés</li>
    <li>Actions réinitialisées à 100 €</li>
</ul>";
echo "<p><a href='logout.php'>Se déconnecter</a></p>";
?>
