<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

redirect_if_not_logged_in();

$id_utilisateur_connecte = $_SESSION['user_id'];

// Récupérer les joueurs suivis par l'utilisateur connecté
$stmt_suivis = $pdo->prepare("
    SELECT U.id, U.email 
    FROM SUIVI_JOUEUR SJ
    JOIN UTILISATEUR U ON SJ.id_suivi = U.id
    WHERE SJ.id_suiveur = ?
");
$stmt_suivis->execute([$id_utilisateur_connecte]);
$joueurs_suivis = $stmt_suivis->fetchAll(PDO::FETCH_ASSOC);

// Pour chaque joueur suivi, récupérer son historique de classement (simplifié pour l'exemple)
// Dans une application réelle, on voudrait paginer ou limiter les résultats de l'historique.
$historiques_joueurs = [];
if ($joueurs_suivis) {
    foreach ($joueurs_suivis as $joueur) {
        $stmt_hist = $pdo->prepare("
            SELECT date_snapshot, rang, valeur_portefeuille, performance_mensuelle
            FROM HISTORIQUE_CLASSEMENT_JOUEUR
            WHERE id_joueur = ?
            ORDER BY date_snapshot DESC
            LIMIT 12 -- Afficher les 12 derniers mois par exemple
        ");
        $stmt_hist->execute([$joueur['id']]);
        $historiques_joueurs[$joueur['id']] = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);
    }
}

require_once '../templates/header.php';
?>

<div class="container">
    <h2>Joueurs Suivis</h2>

    <?php if (empty($joueurs_suivis)): ?>
        <p>Vous ne suivez actuellement aucun joueur. Vous pouvez suivre des joueurs depuis la page <a href="classement.php">Classement</a>.</p>
    <?php else: ?>
        <?php foreach ($joueurs_suivis as $joueur): ?>
            <div class="joueur-suivi-card">
                <h3><?= htmlspecialchars($joueur['email']) ?></h3>
                <?php if (!empty($historiques_joueurs[$joueur['id']])): ?>
                    <h4>Historique de progression (mensuelle) :</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Rang</th>
                                <th>Valeur Portefeuille (€)</th>
                                <th>Performance Mensuelle (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historiques_joueurs[$joueur['id']] as $hist): ?>
                                <tr>
                                    <td><?= htmlspecialchars($hist['date_snapshot']) ?></td>
                                    <td><?= htmlspecialchars($hist['rang'] ?: 'N/A') ?></td>
                                    <td><?= number_format($hist['valeur_portefeuille'] ?: 0, 2) ?></td>
                                    <td><?= htmlspecialchars($hist['performance_mensuelle'] ?: 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun historique de progression disponible pour ce joueur.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="navigation-buttons" style="margin-top: 20px;">
        <a href="dashboard.php" class="secondary-button">Retour au tableau de bord</a>
        <a href="classement.php" class="secondary-button">Voir le classement</a>
    </div>
</div>

<style>
.joueur-suivi-card {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    background-color: #f9f9f9;
}
.joueur-suivi-card h3 {
    margin-top: 0;
}
.joueur-suivi-card table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.joueur-suivi-card th, .joueur-suivi-card td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
.joueur-suivi-card th {
    background-color: #f0f0f0;
}
</style>

<?php require_once '../templates/footer.php'; ?>

