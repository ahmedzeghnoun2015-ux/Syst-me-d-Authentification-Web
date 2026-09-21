<?php
require_once __DIR__ . '/includes/auth.php';
require_guest();
$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['mot_de_passe'] ?? '');
    $stmt = db()->prepare('SELECT * FROM utilisateurs WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        $errors[] = 'Email ou mot de passe incorrect.';
    } else {
        login_user($user);
        redirect($user['role'] === 'ADMIN' ? 'admin/dashboard.php' : 'dashboard.php');
    }
}
$pageTitle = 'Connexion';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout">
    <div class="auth-card">
        <p class="eyebrow">BON RETOUR</p><h1>Connexion</h1>
        <?php if ($errors): ?><div class="alert alert-error"><?= e($errors[0]) ?></div><?php endif; ?>
        <form method="post" data-auth-form>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label for="email">Adresse email</label>
            <input id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email">
            <label for="mot_de_passe">Mot de passe</label>
            <input id="mot_de_passe" name="mot_de_passe" type="password" required autocomplete="current-password">
            <button class="btn btn-block" type="submit">Se connecter</button>
        </form>
        <a class="helper-link" href="forgot-password.php">Mot de passe oublié ?</a>
        <p class="form-footer">Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
