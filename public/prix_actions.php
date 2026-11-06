<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$actions = $pdo->query("SELECT id, nom FROM ACTION ORDER BY nom")->fetchAll();
$selected_id = isset($_GET['action_id']) ? (int)$_GET['action_id'] : null;
$historique = [];

if ($selected_id) {
    $stmt = $pdo->prepare("SELECT date, prix FROM HISTORIQUE_PRIX WHERE action_id = ? ORDER BY date ASC");
    $stmt->execute([$selected_id]);
    $historique = $stmt->fetchAll();
}

require_once '../templates/header.php';
?>

<div class="container">
    <h2>Évolution du prix d'une action</h2>

    <form method="GET">
        <label for="action_id">Choisir une action :</label>
        <select name="action_id" id="action_id" onchange="this.form.submit()">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($actions as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $selected_id == $a['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selected_id && $historique): ?>
        <canvas id="priceChart" height="100"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('priceChart').getContext('2d');
            const priceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($historique, 'date')) ?>,
                    datasets: [{
                        label: 'Prix (€)',
                        data: <?= json_encode(array_map('floatval', array_column($historique, 'prix'))) ?>,
                        borderColor: '#0077cc',
                        backgroundColor: 'rgba(0, 119, 204, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Prix en €'
                            }
                        }
                    }
                }
            });
        </script>
    <?php elseif ($selected_id): ?>
        <p>Aucune donnée de prix pour cette action.</p>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>
