<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_secret = '6Le5bh8rAAAAAKgxXpw14oVRHoP9MoR9mk7_-eEk';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    
    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
    $response_data = json_decode($verify_response);
    
    if (!$response_data->success) {
        $error = "Veuillez valider le reCAPTCHA.";
    } else {
        $email = $_POST['email'];
        $password = $_POST['mot_de_passe'];

        $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}

require_once '../templates/header.php';
?>

<link rel="stylesheet" href="css/auth-styles.css">

<div class="page-container">
    <div class="form-container">
        <h2 class="form-title">Connexion</h2>
        
        <form method="POST" class="centered-form">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6Le5bh8rAAAAAGpV-NR5Mc2KkpoUFCbo5s4Nc8cR"></div>
            
            <button type="submit" class="primary-button">Se connecter</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="message error"><?= $error ?></p>
        <?php endif; ?>
        
        <div class="navigation-buttons">
            <a href="index.php" class="secondary-button">Retour à l'accueil</a>
            <a href="register.php" class="secondary-button">Créer un compte</a>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php require_once '../templates/footer.php'; ?>
