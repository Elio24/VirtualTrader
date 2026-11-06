<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_id = (int)$_POST['action_id'];
    $quantite = (int)$_POST['quantite'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("SELECT * FROM ACTION WHERE id = ?");
    $stmt->execute([$action_id]);
    $action = $stmt->fetch();

    if ($action) {
        $prix_unitaire = $action['prix_actuel'];
        $montant_total = $prix_unitaire * $quantite;

        if ($type === 'achat') {
            $stmt = $pdo->prepare("SELECT liquidites FROM UTILISATEUR WHERE id = ?");
            $stmt->execute([$user_id]);
            $liquidites = $stmt->fetchColumn();

            if ($liquidites >= $montant_total) {
                $pdo->beginTransaction();

                $pdo->prepare("UPDATE UTILISATEUR SET liquidites = liquidites - ? WHERE id = ?")
                    ->execute([$montant_total, $user_id]);

                $stmt = $pdo->prepare("SELECT id FROM POSSESSION_ACTION WHERE utilisateur_id = ? AND action_id = ?");
                $stmt->execute([$user_id, $action_id]);
                $possession = $stmt->fetch();

                if ($possession) {
                    $pdo->prepare("
                        UPDATE POSSESSION_ACTION 
                        SET quantite = quantite + ?, prix_moyen_achat = ? 
                        WHERE utilisateur_id = ? AND action_id = ?
                    ")->execute([$quantite, $prix_unitaire, $user_id, $action_id]);
                } else {
                    $pdo->prepare("
                        INSERT INTO POSSESSION_ACTION (utilisateur_id, action_id, quantite, prix_moyen_achat)
                        VALUES (?, ?, ?, ?)
                    ")->execute([$user_id, $action_id, $quantite, $prix_unitaire]);
                }

                $pdo->prepare("
                    INSERT INTO TRANSACTION (utilisateur_id, action_id, type, quantite, prix_unitaire, montant_total)
                    VALUES (?, ?, 'achat', ?, ?, ?)
                ")->execute([$user_id, $action_id, $quantite, $prix_unitaire, $montant_total]);

                $pdo->commit();
                $message = "Achat réussi.";
                $msg_class = "success";
            } else {
                $message = "Fonds insuffisants.";
                $msg_class = "error";
            }
        }

        if ($type === 'vente') {
            $stmt = $pdo->prepare("SELECT quantite FROM POSSESSION_ACTION WHERE utilisateur_id = ? AND action_id = ?");
            $stmt->execute([$user_id, $action_id]);
            $possession = $stmt->fetch();

            if ($possession && $possession['quantite'] >= $quantite) {
                $pdo->beginTransaction();

                $pdo->prepare("UPDATE UTILISATEUR SET liquidites = liquidites + ? WHERE id = ?")
                    ->execute([$montant_total, $user_id]);

                $pdo->prepare("
                    UPDATE POSSESSION_ACTION 
                    SET quantite = quantite - ? 
                    WHERE utilisateur_id = ? AND action_id = ?
                ")->execute([$quantite, $user_id, $action_id]);

                $pdo->prepare("
                    INSERT INTO TRANSACTION (utilisateur_id, action_id, type, quantite, prix_unitaire, montant_total)
                    VALUES (?, ?, 'vente', ?, ?, ?)
                ")->execute([$user_id, $action_id, $quantite, $prix_unitaire, $montant_total]);

                $pdo->commit();
                $message = "Vente réussie.";
                $msg_class = "success";
            } else {
                $message = "Vous ne possédez pas suffisamment d’actions.";
                $msg_class = "error";
            }
        }
    }
}

$actions = $pdo->query("SELECT * FROM ACTION ORDER BY nom ASC")->fetchAll();
require_once '../templates/header.php';
?>

<div class="container">
    <h2>Achat / Vente d'Actions</h2>

    <?php if (isset($message)): ?>
        <div class="message <?= $msg_class ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="action_id">Action :</label>
        <select name="action_id" required>
            <option value="" selected disabled>-- Sélectionner une action --</option>            <?php foreach ($actions as $a): ?>
                <option value="<?= $a['id'] ?>">
                    <?= htmlspecialchars($a['nom']) ?> (<?= $a['code'] ?>) - <?= $a['prix_actuel'] ?> €
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" min="1" required>

        <label for="type">Type :</label>
        <select name="type">
            <option value="achat">Achat</option>
            <option value="vente">Vente</option>
        </select>

        <button type="submit">Valider</button>
    </form>
</div>

<?php require_once '../templates/footer.php'; ?>
