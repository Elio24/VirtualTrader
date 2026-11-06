<?php
require_once '../config/database.php';

$action_id = 1; // ID d'une action existante
$date_test = '2025-06-01'; // date à laquelle la simulation commence
$montant = 1.50; // dividende par action

$stmt = $pdo->prepare("UPDATE ACTION SET montant_dividende = ?, date_dividende = ? WHERE id = ?");
$stmt->execute([$montant, $date_test, $action_id]);

echo "✅ Dividende forcé sur l'action ID $action_id pour le $date_test avec montant $montant €.";
?>
