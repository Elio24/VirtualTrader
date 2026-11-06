<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

redirect_if_not_logged_in();

$id_utilisateur_connecte = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Traitement de la demande de prêt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'demander_pret') {
    $montant_demande = filter_input(INPUT_POST, 'montant_emprunt', FILTER_VALIDATE_FLOAT);
    $duree_mois = filter_input(INPUT_POST, 'duree_mois', FILTER_VALIDATE_INT);
    $taux_interet_annuel_propose = 5.0; // Exemple de taux fixe, pourrait être dynamique

    if ($montant_demande && $montant_demande > 0 && $duree_mois && $duree_mois > 0) {
        // Vérifier l'éligibilité (exemple simple : ne pas avoir trop de prêts actifs ou un plafond)
        $stmt_check_pret = $pdo->prepare("SELECT COUNT(*) as nb_prets_actifs FROM PRETS WHERE id_utilisateur = ? AND statut_pret = 'ACTIF'");
        $stmt_check_pret->execute([$id_utilisateur_connecte]);
        $nb_prets_actifs = $stmt_check_pret->fetchColumn();

        // Récupérer le solde liquide et la valeur du portefeuille pour le plafond
        $stmt_valeur_portefeuille = $pdo->prepare("
            SELECT U.liquidites, COALESCE(SUM(PA.quantite * A.prix_actuel), 0) AS valeur_actions
            FROM UTILISATEUR U
            LEFT JOIN POSSESSION_ACTION PA ON U.id = PA.utilisateur_id
            LEFT JOIN ACTION A ON A.id = PA.action_id
            WHERE U.id = ?
            GROUP BY U.id
        ");
        $stmt_valeur_portefeuille->execute([$id_utilisateur_connecte]);
        $portefeuille_info = $stmt_valeur_portefeuille->fetch(PDO::FETCH_ASSOC);
        $valeur_totale_portefeuille = ($portefeuille_info['liquidites'] ?? 0) + ($portefeuille_info['valeur_actions'] ?? 0);
        $plafond_emprunt = $valeur_totale_portefeuille * 0.25; // Plafond à 25% de la valeur totale du portefeuille

        if ($nb_prets_actifs < 3 && $montant_demande <= $plafond_emprunt) { // Limite de 3 prêts actifs et respect du plafond
            $date_emprunt = date('Y-m-d H:i:s');
            $date_echeance = date('Y-m-d H:i:s', strtotime("+" . $duree_mois . " months"));

            $stmt_insert_pret = $pdo->prepare("
                INSERT INTO PRETS (id_utilisateur, montant_emprunte, taux_interet_annuel, date_emprunt, date_echeance, statut_pret)
                VALUES (?, ?, ?, ?, ?, 'ACTIF')
            ");
            if ($stmt_insert_pret->execute([$id_utilisateur_connecte, $montant_demande, $taux_interet_annuel_propose, $date_emprunt, $date_echeance])) {
                // Mettre à jour le solde liquide de l'utilisateur
                $stmt_update_solde = $pdo->prepare("UPDATE UTILISATEUR SET liquidites = liquidites + ? WHERE id = ?");
                if ($stmt_update_solde->execute([$montant_demande, $id_utilisateur_connecte])) {
                    $success_message = "Votre demande de prêt de " . number_format($montant_demande, 2) . " € a été approuvée et les fonds ont été ajoutés à votre compte.";
                } else {
                    // Annuler le prêt si la mise à jour du solde échoue (principe de transaction)
                    $pdo->exec("DELETE FROM PRETS WHERE id_pret = " . $pdo->lastInsertId());
                    $error_message = "Erreur lors de la mise à jour de votre solde. Le prêt n'a pas été accordé.";
                }
            } else {
                $error_message = "Une erreur s'est produite lors de l'enregistrement de votre demande de prêt.";
            }
        } else {
             if ($nb_prets_actifs >= 3) {
                $error_message = "Vous avez atteint le nombre maximal de prêts actifs (3).";
            } else {
                $error_message = "Le montant demandé (" . number_format($montant_demande, 2) . " €) dépasse votre plafond d'emprunt autorisé (" . number_format($plafond_emprunt, 2) . " €).";
            }
        }
    } else {
        $error_message = "Veuillez saisir un montant et une durée valides pour le prêt.";
    }
}

// Récupérer les prêts de l'utilisateur
$stmt_prets = $pdo->prepare("SELECT *, (montant_emprunte * (1 + (taux_interet_annuel / 100 / 12 * (TIMESTAMPDIFF(MONTH, date_emprunt, NOW()))))) as montant_a_rembourser_estime FROM PRETS WHERE id_utilisateur = ? ORDER BY date_emprunt DESC");
$stmt_prets->execute([$id_utilisateur_connecte]);
$prets_utilisateur = $stmt_prets->fetchAll(PDO::FETCH_ASSOC);

require_once '../templates/header.php';
?>

<link rel="stylesheet" href="css/auth-styles.css"> 
<style>
.loan-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.loan-section h3 {
    margin-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
}
.loan-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.loan-table th, .loan-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
.loan-table th {
    background-color: #f0f0f0;
}
.statut-ACTIF { color: orange; font-weight: bold; }
.statut-REMBOURSE { color: green; font-weight: bold; }
.statut-DEFAUT { color: red; font-weight: bold; }
</style>

<div class="page-container">
    <div class="form-container" style="max-width: 800px;">
        <h2 class="form-title">Système d'Emprunt</h2>

        <?php if ($error_message): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <div class="loan-section">
            <h3>Demander un nouveau prêt</h3>
            <form method="POST" action="emprunt.php" class="centered-form">
                <input type="hidden" name="action" value="demander_pret">
                <div class="form-group">
                    <label for="montant_emprunt">Montant à emprunter (€) :</label>
                    <input type="number" id="montant_emprunt" name="montant_emprunt" step="0.01" min="1" required>
                </div>
                <div class="form-group">
                    <label for="duree_mois">Durée du prêt (en mois) :</label>
                    <input type="number" id="duree_mois" name="duree_mois" min="1" max="60" required>
                </div>
                <p><small>Taux d'intérêt annuel fixe : 5.00%. Plafond d'emprunt : 25% de la valeur totale de votre portefeuille. Maximum 3 prêts actifs.</small></p>
                <button type="submit" class="primary-button">Demander le prêt</button>
            </form>
        </div>

        <div class="loan-section">
            <h3>Mes prêts en cours et passés</h3>
            <?php if (empty($prets_utilisateur)): ?>
                <p>Vous n'avez aucun prêt actuellement.</p>
            <?php else: ?>
                <table class="loan-table">
                    <thead>
                        <tr>
                            <th>Date Emprunt</th>
                            <th>Montant Emprunté (€)</th>
                            <th>Taux Annuel (%)</th>
                            <th>Échéance</th>
                            <th>Montant Remboursé (€)</th>
                            <th>Montant à Rembourser (Estimé) (€)</th>
                            <th>Statut</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prets_utilisateur as $pret): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($pret['date_emprunt']))) ?></td>
                                <td><?= number_format($pret['montant_emprunte'], 2) ?></td>
                                <td><?= number_format($pret['taux_interet_annuel'], 2) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($pret['date_echeance']))) ?></td>
                                <td><?= number_format($pret['montant_rembourse'], 2) ?></td>
                                <td><?= number_format($pret['montant_a_rembourser_estime'], 2) ?></td>
                                <td class="statut-<?= htmlspecialchars($pret['statut_pret']) ?>"><?= htmlspecialchars($pret['statut_pret']) ?></td>
                                
                                    <?php  ?>
                                        <button class="button-small">Rembourser</button> 
                                    <?php  ?>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                 <p><small>Le montant à rembourser est une estimation basée sur un calcul simple des intérêts mensuels et n'inclut pas les remboursements partiels pour le moment.</small></p>
            <?php endif; ?>
        </div>

        <div class="navigation-buttons" style="margin-top: 20px;">
            <a href="dashboard.php" class="secondary-button">Retour au tableau de bord</a>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>

