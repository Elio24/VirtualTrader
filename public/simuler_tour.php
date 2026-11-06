<?php
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

// On récupère le dernier mois simulé
$stmt = $pdo->query("SELECT MAX(date) FROM HISTORIQUE_PORTEFEUILLE");
$last_date = $stmt->fetchColumn();
$current_month = $last_date ? $last_date : date(
    'Y-m-01'); 
$next_month = date('Y-m-01', strtotime($current_month . ' +1 month'));


// 2. Mise à jour des prix des actions
$actions = $pdo->query("SELECT * FROM ACTION")->fetchAll();

foreach ($actions as $a) {
    $ancienne_evo = $a['evolution_precedente'];
    $min_change = -3;
    $max_change = 3;
    $random_change = rand($min_change * 100, $max_change * 100) / 100; // Changement aléatoire entre -3 et +3 points
    $nouvelle_evo_base = $ancienne_evo + $random_change;
    
    // évolution entre -10% et +10%
    $nouvelle_evo = max(-10, min(10, $nouvelle_evo_base));

    $ancien_prix = $a['prix_actuel'];
    $nouveau_prix = $ancien_prix * (1 + $nouvelle_evo / 100);
    // Assurer que le prix ne descend pas en dessous de 1€
    if ($nouveau_prix < 1) $nouveau_prix = 1;

    $stmt = $pdo->prepare("UPDATE ACTION SET prix_actuel = ?, evolution_precedente = ? WHERE id = ?");
    $stmt->execute([round($nouveau_prix, 2), $nouvelle_evo, $a['id']]);

    // Historique des prix
    $stmt = $pdo->prepare("INSERT INTO HISTORIQUE_PRIX (action_id, prix, date) VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE prix = ?");
    $stmt->execute([$a['id'], round($nouveau_prix, 2), $next_month, round($nouveau_prix, 2)]);
}

// 3. Dividendes
$dividend_actions = $pdo->prepare("
    SELECT A.id, A.montant_dividende, PA.utilisateur_id, PA.quantite
    FROM ACTION A
    JOIN POSSESSION_ACTION PA ON A.id = PA.action_id
    WHERE A.date_dividende = ? AND PA.quantite > 0
");
$dividend_actions->execute([$next_month]);

foreach ($dividend_actions as $row) {
    $gain = $row['montant_dividende'] * $row['quantite'];
    if ($gain > 0) { 
        $pdo->prepare("UPDATE UTILISATEUR SET liquidites = liquidites + ? WHERE id = ?")
            ->execute([$gain, $row['utilisateur_id']]);
            
        // Enregistrer le dividende reçu
        $pdo->prepare("INSERT INTO DIVIDENDE_RECU (utilisateur_id, action_id, montant, quantite, date)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE montant = montant + VALUES(montant), quantite = quantite + VALUES(quantite)") 
        ->execute([$row['utilisateur_id'], $row['id'], $gain, $row['quantite'], $next_month]);
    }
}

// 4. Historique portefeuille (pour chaque utilisateur)
$users = $pdo->query("SELECT * FROM UTILISATEUR")->fetchAll();

foreach ($users as $user) {
    $user_id = $user['id'];
    
    // Récupérer les liquidités à jour après les dividendes potentiels
    $stmt_liquidites = $pdo->prepare("SELECT liquidites FROM UTILISATEUR WHERE id = ?");
    $stmt_liquidites->execute([$user_id]);
    $liquidites = $stmt_liquidites->fetchColumn();

    $stmt_valeur = $pdo->prepare("
        SELECT SUM(PA.quantite * A.prix_actuel) AS valeur_actions
        FROM POSSESSION_ACTION PA
        JOIN ACTION A ON A.id = PA.action_id
        WHERE PA.utilisateur_id = ?
    ");
    $stmt_valeur->execute([$user_id]);
    $valeur_actions = $stmt_valeur->fetchColumn() ?: 0;

    $valeur_totale = $liquidites + $valeur_actions;

    $stmt_hist = $pdo->prepare("
        INSERT INTO HISTORIQUE_PORTEFEUILLE (utilisateur_id, valeur, date)
        VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE valeur = ?
    ");
    $stmt_hist->execute([$user_id, $valeur_totale, $next_month, $valeur_totale]);
}

if (php_sapi_name() === 'cli') {
    echo "Tour simulé : nous sommes maintenant en " . date('F Y', strtotime($next_month)) . ".\n";
} else {
    $message = "Tour simulé : nous sommes maintenant en " . date('F Y', strtotime($next_month)) . ".";
    echo "<div class=\"container\"><h2>Simulation du tour</h2><p>$message</p><p><a href=\"dashboard.php\">⬅ Retour au tableau de bord</a></p></div>";
}

?>
