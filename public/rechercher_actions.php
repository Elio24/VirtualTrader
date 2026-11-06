<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$conditions = [];
$params = [];

if (!empty($_GET['nom'])) {
    $conditions[] = "nom LIKE ?";
    $params[] = "%" . $_GET['nom'] . "%";
}

if (!empty($_GET['prix_min'])) {
    $conditions[] = "prix_actuel >= ?";
    $params[] = $_GET['prix_min'];
}

if (!empty($_GET['prix_max'])) {
    $conditions[] = "prix_actuel <= ?";
    $params[] = $_GET['prix_max'];
}

if (!empty($_GET['progression'])) {
    $conditions[] = "evolution_precedente >= ?";
    $params[] = $_GET['progression'];
}

$sql = "SELECT * FROM ACTION";
if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY nom ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$actions = $stmt->fetchAll();

require_once '../templates/header.php';
?>

<div class="container">
    <h2>Rechercher des actions</h2>

    <form method="GET">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= $_GET['nom'] ?? '' ?>"><br>

        <label>Prix min :</label>
        <input type="number" step="0.01" name="prix_min" value="<?= $_GET['prix_min'] ?? '' ?>"><br>

        <label>Prix max :</label>
        <input type="number" step="0.01" name="prix_max" value="<?= $_GET['prix_max'] ?? '' ?>"><br>

        <label>Progression mensuelle minimale (%) :</label>
        <input type="number" step="0.01" name="progression" value="<?= $_GET['progression'] ?? '' ?>"><br>

        <button type="submit">Filtrer</button>
    </form>

    <?php if ($actions): ?>
        <h3>Résultats :</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Code</th>
                <th>Prix actuel</th>
                <th>Évolution mensuelle</th>
            </tr>
            <?php foreach ($actions as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['code']) ?></td>
                    <td><?= number_format($a['prix_actuel'], 2) ?> €</td>
                    <td><?= number_format($a['evolution_precedente'], 2) ?> %</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune action trouvée selon les critères.</p>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>
