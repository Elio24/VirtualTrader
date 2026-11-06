<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];

// Récupère l'historique (dernier 12 mois)
$stmt = $pdo->prepare("
    SELECT date, valeur 
    FROM HISTORIQUE_PORTEFEUILLE 
    WHERE utilisateur_id = ? 
    ORDER BY date ASC
");
$stmt->execute([$user_id]);
$historique = $stmt->fetchAll();

require_once '../templates/header.php';
?>

<div class="container">
    <h2>Historique du portefeuille (12 derniers mois)</h2>

    <?php if ($historique): ?>
        <canvas id="portefeuilleChart" height="100" style="margin-bottom: 40px;"></canvas>

        <table>
            <tr>
                <th>Date</th>
                <th>Valeur (€)</th>
            </tr>
            <?php foreach ($historique as $ligne): ?>
                <tr>
                    <td><?= $ligne['date'] ?></td>
                    <td><?= number_format($ligne['valeur'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('portefeuilleChart').getContext('2d');
            const portefeuilleChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($historique, 'date')) ?>,
                    datasets: [{
                        label: 'Valeur totale (€)',
                        data: <?= json_encode(array_map('floatval', array_column($historique, 'valeur'))) ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
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
                                text: 'Valeur (€)'
                            }
                        }
                    }
                }
            });
        </script>
    <?php else: ?>
        <p>Aucun historique disponible pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>
