<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$id_utilisateur_connecte = $_SESSION['user_id'];

// Récupérer les messages de la session et les effacer
$success_message_suivi = isset($_SESSION['success_message_suivi']) ? $_SESSION['success_message_suivi'] : '';
$error_message_suivi = isset($_SESSION['error_message_suivi']) ? $_SESSION['error_message_suivi'] : '';
unset($_SESSION['success_message_suivi']);
unset($_SESSION['error_message_suivi']);

// Récupérer la liste des joueurs que l'utilisateur connecté suit
$stmt_suivis = $pdo->prepare("SELECT id_suivi FROM SUIVI_JOUEUR WHERE id_suiveur = ?");
$stmt_suivis->execute([$id_utilisateur_connecte]);
$joueurs_suivis_ids = $stmt_suivis->fetchAll(PDO::FETCH_COLUMN);


//liquidités + somme(prix_actuel * quantité)
$sql = "
SELECT 
    U.id,
    U.email,
    U.liquidites,
    COALESCE(SUM(PA.quantite * A.prix_actuel), 0) AS valeur_actions,
    (U.liquidites + COALESCE(SUM(PA.quantite * A.prix_actuel), 0)) AS valeur_totale
FROM UTILISATEUR U
LEFT JOIN POSSESSION_ACTION PA ON U.id = PA.utilisateur_id
LEFT JOIN ACTION A ON A.id = PA.action_id
GROUP BY U.id
ORDER BY valeur_totale DESC
";

$classement = $pdo->query($sql)->fetchAll();
require_once '../templates/header.php';
?>

<div class="container">
    <h2>Classement des joueurs</h2>

    <?php if ($success_message_suivi): ?>
        <p class="message success"><?= htmlspecialchars($success_message_suivi) ?></p>
    <?php endif; ?>
    <?php if ($error_message_suivi): ?>
        <p class="message error"><?= htmlspecialchars($error_message_suivi) ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Liquidités (€)</th>
            <th>Valeur actions (€)</th>
            <th>Valeur totale (€)</th>
            <th>Action</th>
        </tr>
        <?php foreach ($classement as $index => $joueur): ?>
            <tr<?= ($joueur['id'] == $id_utilisateur_connecte) ? ' style="background-color: #e8f5e9;"' : '' ?>>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($joueur['email']) ?></td>
                <td><?= number_format($joueur['liquidites'], 2) ?></td>
                <td><?= number_format($joueur['valeur_actions'], 2) ?></td>
                <td><strong><?= number_format($joueur['valeur_totale'], 2) ?></strong></td>
                <td>
                    <?php if ($joueur['id'] != $id_utilisateur_connecte): ?>
                        <form action="gerer_suivi.php" method="POST" style="display: inline-block; text-align: center;">
                            <input type="hidden" name="id_utilisateur_a_suivre" value="<?= $joueur['id'] ?>">
                            <?php if (in_array($joueur['id'], $joueurs_suivis_ids)): ?>
                                <button type="submit" name="action" value="ne_plus_suivre" class="btn btn-danger btn-sm">Ne plus suivre</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="suivre" class="btn btn-success btn-sm">Suivre</button>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <span class="vous-label">Vous</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<style>
.btn-sm {
    padding: 0.4rem 0.8rem; 
    font-size: .875rem;
    line-height: 1.5;
    border-radius: 20px; 
    margin: 2px auto; 
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s ease-in-out;
    font-weight: 500; 
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: inline-block; /* Assure que le bouton ne prend pas toute la largeur */
    min-width: 120px; /* Largeur minimale pour une bonne lisibilité */
    text-align: center;
}

.btn-danger {
    color: #fff;
    background-color: #e74c3c; 
    border-color: #e74c3c;
}

.btn-danger:hover {
    background-color: #c0392b; 
    border-color: #c0392b;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transform: translateY(-1px);
}

.btn-success {
    color: #fff;
    background-color: #2ecc71; 
    border-color: #2ecc71;
}

.btn-success:hover {
    background-color: #27ae60; 
    border-color: #27ae60;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transform: translateY(-1px);
}

.vous-label {
    font-weight: bold;
    color: #3498db; /* Bleu pour "Vous" */
    padding: 0.375rem 0.75rem;
    display: inline-block;
    background-color: #ecf0f1; 
    border-radius: 20px;
    border: 1px solid #bdc3c7;
    min-width: 120px;
    text-align: center;
}

.message.success {
    background-color: #d1e7dd;
    color: #0f5132;
    padding: 1rem;
    border: 1px solid #badbcc;
    border-radius: .25rem;
    margin-bottom: 1rem;
}

.message.error {
    background-color: #f8d7da;
    color: #842029;
    padding: 1rem;
    border: 1px solid #f5c2c7;
    border-radius: .25rem;
    margin-bottom: 1rem;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    vertical-align: middle; /* Aligner verticalement au centre */
}

td:last-child {
    text-align: center; /* Centrer le contenu de la dernière colonne (Action) */
}

th {
    background-color: #f8f9fa;
    font-weight: bold;
    color: #495057;
}

tr:hover {
    background-color: #f1f1f1;
}

tr[style*="background-color: #e8f5e9;"]:hover {
    background-color: #dcf0dc !important;
}

</style>

<?php require_once '../templates/footer.php'; ?>

