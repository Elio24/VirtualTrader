<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

redirect_if_not_logged_in();

$id_utilisateur_connecte = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_utilisateur_a_suivre']) && isset($_POST['action'])) {
    $id_utilisateur_a_suivre = filter_input(INPUT_POST, 'id_utilisateur_a_suivre', FILTER_VALIDATE_INT);
    $action = $_POST['action']; // 'suivre' ou 'ne_plus_suivre'

    if ($id_utilisateur_a_suivre && $id_utilisateur_a_suivre != $id_utilisateur_connecte) {
        if ($action === 'suivre') {
            // Vérifier si l'utilisateur n'est pas déjà suivi
            $stmt_check = $pdo->prepare("SELECT * FROM SUIVI_JOUEUR WHERE id_suiveur = ? AND id_suivi = ?");
            $stmt_check->execute([$id_utilisateur_connecte, $id_utilisateur_a_suivre]);
            if ($stmt_check->fetch()) {
                $error = "Vous suivez déjà cet utilisateur.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO SUIVI_JOUEUR (id_suiveur, id_suivi, date_debut_suivi) VALUES (?, ?, CURDATE())");
                if ($stmt->execute([$id_utilisateur_connecte, $id_utilisateur_a_suivre])) {
                    $message = "Vous suivez maintenant cet utilisateur.";
                } else {
                    $error = "Erreur lors du suivi de l'utilisateur.";
                }
            }
        } elseif ($action === 'ne_plus_suivre') {
            $stmt = $pdo->prepare("DELETE FROM SUIVI_JOUEUR WHERE id_suiveur = ? AND id_suivi = ?");
            if ($stmt->execute([$id_utilisateur_connecte, $id_utilisateur_a_suivre])) {
                $message = "Vous ne suivez plus cet utilisateur.";
            } else {
                $error = "Erreur lors de l'arrêt du suivi de l'utilisateur.";
            }
        } else {
            $error = "Action non valide.";
        }
    } else {
        $error = "ID utilisateur invalide ou vous ne pouvez pas vous suivre vous-même.";
    }
    // Rediriger vers la page de classement ou la page d'où vient la requête
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'classement.php';
    // Ajouter les messages à la session pour les afficher après la redirection
    if ($message) $_SESSION['success_message_suivi'] = $message;
    if ($error) $_SESSION['error_message_suivi'] = $error;
    header("Location: " . $redirect_url);
    exit;
} else {
    // Si la page est accédée directement sans POST, rediriger
    header("Location: classement.php");
    exit;
}
?>
