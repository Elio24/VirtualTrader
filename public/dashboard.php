<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';
redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("\n    SELECT A.nom, A.code, PA.quantite, A.prix_actuel, PA.prix_moyen_achat\n    FROM POSSESSION_ACTION PA\n    JOIN ACTION A ON A.id = PA.action_id\n    WHERE PA.utilisateur_id = ?\n");
$stmt->execute([$user_id]);
$actions = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT MAX(date) AS `current_date` FROM HISTORIQUE_PORTEFEUILLE WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$current_date = $stmt->fetchColumn();
$current_date_formatted = $current_date ? date('F Y', strtotime($current_date)) : 'Non défini';

require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<div class="conteneur" style="padding-top: 30px; padding-bottom: 30px;">
    
    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #0056b3;">
        <h2>Bienvenue, <?= htmlspecialchars($_SESSION['email']) ?></h2>
        <p><strong>Liquidités :</strong> <?= number_format($user['liquidites'], 2) ?> €</p>
        <p><strong>Date simulée actuelle :</strong> <?= $current_date_formatted ?></p>
    </div>

    <?php if ($user['email'] == 'admin@gmail.com'): // Zone Admin spéciale pour admin@gmail.com ?>
        <div style="background-color: #fff0f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid red;">
            <h3 style="color:red;">Zone admin</h3>
            <p>Actions réservées à l'administrateur.</p>
            <a href="reset_complet_avec_utilisateurs.php" class="bouton-lien" style="background-color: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;" onclick="return confirm('Êtes-vous sûr ? Cela supprimera TOUT !')">Réinitialiser complètement le projet</a>
        </div>
    <?php endif; ?>

    <div>
        <h3>Mon Portefeuille</h3>
        <?php if (count($actions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Quantité</th>
                        <th>Prix actuel / action</th>
                        <th>Prix moyen d’achat / action</th>
                        <th>Valeur totale</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $valeur_totale_portefeuille_actions = 0;
                    foreach ($actions as $action): 
                        $valeur_action = $action['quantite'] * $action['prix_actuel'];
                        $valeur_totale_portefeuille_actions += $valeur_action;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($action['nom']) ?></td>
                            <td><?= htmlspecialchars($action['code']) ?></td>
                            <td><?= $action['quantite'] ?></td>
                            <td><?= number_format($action['prix_actuel'], 2) ?> €</td>
                            <td><?= number_format($action['prix_moyen_achat'], 2) ?> €</td>
                            <td><?= number_format($valeur_action, 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                 <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Valeur totale des actions :</td>
                        <td style="font-weight: bold;"><?= number_format($valeur_totale_portefeuille_actions, 2) ?> €</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Liquidités :</td>
                        <td style="font-weight: bold;"><?= number_format($user['liquidites'], 2) ?> €</td>
                    </tr>
                     <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold; color: #003366; font-size: 1.1em;">Valeur totale du portefeuille :</td>
                        <td style="font-weight: bold; color: #003366; font-size: 1.1em;"><?= number_format($valeur_totale_portefeuille_actions + $user['liquidites'], 2) ?> €</td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <div class="message" style="background-color: #eef2f5; border-left: 4px solid #0077cc; color: #003366; padding: 15px; border-radius: 5px;">
                <p>Vous ne possédez aucune action dans votre portefeuille pour le moment.</p>
                <p>Utilisez la section <a href="buy_sell.php" style="color: #0056b3; font-weight: bold;">Acheter/Vendre</a> pour commencer à trader !</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php 
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; 
?>

