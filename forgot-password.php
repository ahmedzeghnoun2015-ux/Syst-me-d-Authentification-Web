<?php
require_once __DIR__ . '/includes/auth.php';
require_guest();
$message = null; $demoLink = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = db()->prepare('SELECT id FROM utilisateurs WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $update = db()->prepare('UPDATE utilisateurs SET token_recuperation = ?, token_recuperation_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?');
            $update->execute([hash('sha256', $token), $user['id']]);
            $demoLink = 'reset-password.php?token=' . urlencode($token);
        }
    }
    $message = 'Si cette adresse existe, un lien de réinitialisation vient d’être généré.';
}
$pageTitle = 'Mot de passe oublié';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout"><div class="auth-card">
    <p class="eyebrow">RÉCUPÉRATION</p><h1>Mot de passe oublié</h1>
    <p class="muted">Saisissez votre email. En local, le lien simulé sera affiché après l’envoi.</p>
    <?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
    <?php if ($demoLink): ?><div class="demo-link"><strong>Email simulé :</strong><a href="<?= e($demoLink) ?>">Réinitialiser mon mot de passe</a></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="email">Adresse email</label><input id="email" name="email" type="email" required autocomplete="email">
        <button class="btn btn-block" type="submit">Envoyer le lien</button>
    </form>
    <p class="form-footer"><a href="login.php">Retour à la connexion</a></p>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
