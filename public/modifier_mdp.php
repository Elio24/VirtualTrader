<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmation_nouveau_mot_de_passe = $_POST['confirmation_nouveau_mot_de_passe'];

    // Récupérer l'utilisateur actuel
    $stmt_user = $pdo->prepare("SELECT mot_de_passe FROM UTILISATEUR WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch();

    if ($user) {
        // Vérifier l'ancien mot de passe
        if (password_verify($ancien_mot_de_passe, $user['mot_de_passe'])) {
            // Vérifier si les nouveaux mots de passe correspondent
            if ($nouveau_mot_de_passe === $confirmation_nouveau_mot_de_passe) {
                // Vérifier la longueur du nouveau mot de passe (exemple simple)
                if (strlen($nouveau_mot_de_passe) >= 8) {
                    // Hacher le nouveau mot de passe
                    $nouveau_mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);

                    // Mettre à jour le mot de passe dans la base de données
                    $stmt_update = $pdo->prepare("UPDATE UTILISATEUR SET mot_de_passe = ? WHERE id = ?");
                    if ($stmt_update->execute([$nouveau_mot_de_passe_hash, $user_id])) {
                        $success_message = "Votre mot de passe a été modifié avec succès.";
                    } else {
                        $error_message = "Une erreur s'est produite lors de la modification de votre mot de passe. Veuillez réessayer.";
                    }
                } else {
                    $error_message = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
                }
            } else {
                $error_message = "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
            }
        } else {
            $error_message = "L'ancien mot de passe est incorrect.";
        }
    } else {
        $error_message = "Utilisateur non trouvé."; // Ne devrait pas arriver si l'utilisateur est connecté
    }
}

require_once '../templates/header.php';
?>

<link rel="stylesheet" href="css/auth-styles.css">

<div class="page-container">
    <div class="form-container">
        <h2 class="form-title">Modifier le mot de passe</h2>

        <?php if ($error_message): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <form method="POST" action="modifier_mdp.php" class="centered-form">
            <div class="form-group">
                <label for="ancien_mot_de_passe">Ancien mot de passe :</label>
                <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required minlength="8">
            </div>

            <div class="form-group">
                <label for="confirmation_nouveau_mot_de_passe">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmation_nouveau_mot_de_passe" name="confirmation_nouveau_mot_de_passe" required minlength="8">
            </div>

            <button type="submit" class="primary-button">Modifier</button>
        </form>

        <div class="navigation-buttons">
            <a href="dashboard.php" class="secondary-button">Retour au tableau de bord</a>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>

