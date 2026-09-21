<?php
require_once __DIR__ . '/includes/auth.php';
require_guest();
$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$errors = []; $validToken = false; $userId = null;
if (preg_match('/^[a-f0-9]{64}$/', $token)) {
    $stmt = db()->prepare('SELECT id FROM utilisateurs WHERE token_recuperation = ? AND token_recuperation_expire > NOW() LIMIT 1');
    $stmt->execute([hash('sha256', $token)]);
    $user = $stmt->fetch();
    if ($user) { $validToken = true; $userId = $user['id']; }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    verify_csrf();
    $password = (string) ($_POST['mot_de_passe'] ?? '');
    $confirmation = (string) ($_POST['confirmation'] ?? '');
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) $errors[] = 'Le mot de passe doit contenir 8 caractères, une majuscule et un chiffre.';
    if ($password !== $confirmation) $errors[] = 'Les mots de passe ne correspondent pas.';
    if (!$errors) {
        $stmt = db()->prepare('UPDATE utilisateurs SET mot_de_passe = ?, token_recuperation = NULL, token_recuperation_expire = NULL WHERE id = ?');
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $userId]);
        redirect('login.php?reset=1');
    }
}
$pageTitle = 'Réinitialiser le mot de passe';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout"><div class="auth-card">
    <p class="eyebrow">NOUVEAU MOT DE PASSE</p><h1>Réinitialisation</h1>
    <?php if (!$validToken): ?><div class="alert alert-error">Ce lien est invalide ou a expiré.</div><a class="btn btn-block" href="forgot-password.php">Demander un nouveau lien</a>
    <?php else: ?>
        <?php if ($errors): ?><div class="alert alert-error"><?= e($errors[0]) ?></div><?php endif; ?>
        <form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="token" value="<?= e($token) ?>">
            <label for="mot_de_passe">Nouveau mot de passe</label><input id="mot_de_passe" name="mot_de_passe" type="password" required minlength="8">
            <label for="confirmation">Confirmation</label><input id="confirmation" name="confirmation" type="password" required minlength="8">
            <button class="btn btn-block" type="submit">Enregistrer le nouveau mot de passe</button>
        </form>
    <?php endif; ?>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
