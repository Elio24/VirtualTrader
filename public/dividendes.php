<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT date, montant 
    FROM DIVIDENDE_RECU 
    WHERE utilisateur_id = ? 
    ORDER BY date DESC
");
$stmt->execute([$user_id]);
$dividendes = $stmt->fetchAll();

require_once '../templates/header.php';
?>

<div class="container">
    <h2>Dividendes reçus par mois</h2>

    <?php if (count($dividendes) > 0): ?>
        <table>
            <tr>
                <th>Mois</th>
                <th>Montant (€)</th>
            </tr>
            <?php foreach ($dividendes as $d): ?>
                <tr>
                    <td><?= date('F Y', strtotime($d['date'])) ?></td>
                    <td><?= number_format($d['montant'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun dividende reçu pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>
