<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_secret = '6Le5bh8rAAAAAKgxXpw14oVRHoP9MoR9mk7_-eEk';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    
    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
    $response_data = json_decode($verify_response);
    
    if (!$response_data->success) {
        $error = "Veuillez valider le reCAPTCHA.";
    } else {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

        if ($mot_de_passe !== $confirmation_mot_de_passe) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            $password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (email, mot_de_passe, date_inscription) VALUES (?, ?, CURDATE())");

            try {
                $stmt->execute([$email, $password]);
                header("Location: login.php");
                exit;
            } catch (PDOException $e) {
                $error = "Cet email est déjà utilisé.";
            }
        }
    }
}

require_once '../templates/header.php';
?>
<link rel="stylesheet" href="css/auth-styles.css">

<div class="page-container">
    <div class="form-container">
        <h2 class="form-title">Inscription</h2>
        
        <form method="POST" class="centered-form">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            
            <div class="form-group">
                <label for="confirmation_mot_de_passe">Confirmer le mot de passe :</label>
                <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required>
                <p id="message_verification" class="password-match"></p>
            </div>
            <div class="g-recaptcha" data-sitekey="6Le5bh8rAAAAAGpV-NR5Mc2KkpoUFCbo5s4Nc8cR"></div>
            
            <button type="submit" id="bouton_inscription" class="primary-button">S'inscrire</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="message error"><?= $error ?></p>
        <?php endif; ?>
        
        <div class="navigation-buttons">
            <a href="index.php" class="secondary-button">Retour à l'accueil</a>
            <a href="login.php" class="secondary-button">Se connecter</a>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const motDePasse = document.getElementById('mot_de_passe');
  const confirmationMotDePasse = document.getElementById('confirmation_mot_de_passe');
  const messageVerification = document.getElementById('message_verification');
  const boutonInscription = document.getElementById('bouton_inscription');
  
  function verifierMotsDePasse() {
    if (confirmationMotDePasse.value === '') {
      messageVerification.textContent = '';
      messageVerification.classList.remove('valid', 'invalid');
    } else if (motDePasse.value === confirmationMotDePasse.value) {
      messageVerification.textContent = 'Les mots de passe correspondent';
      messageVerification.classList.add('valid');
      messageVerification.classList.remove('invalid');
    } else {
      messageVerification.textContent = 'Les mots de passe ne correspondent pas';
      messageVerification.classList.add('invalid');
      messageVerification.classList.remove('valid');
    }
  }
  
  motDePasse.addEventListener('input', verifierMotsDePasse);
  confirmationMotDePasse.addEventListener('input', verifierMotsDePasse);
});
</script>

<?php require_once '../templates/footer.php'; ?>
