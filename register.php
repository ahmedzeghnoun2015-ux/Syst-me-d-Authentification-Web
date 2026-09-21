<?php
require_once __DIR__ . '/includes/auth.php';
require_guest();
$errors = [];
$values = ['nom' => '', 'prenom' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($values as $key => $value) $values[$key] = trim((string) ($_POST[$key] ?? ''));
    $password = (string) ($_POST['mot_de_passe'] ?? '');
    $confirmation = (string) ($_POST['confirmation'] ?? '');

    if (!$values['nom'] || !$values['prenom']) $errors[] = 'Le nom et le prénom sont obligatoires.';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'L’adresse email est invalide.';
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = 'Le mot de passe doit contenir 8 caractères, une majuscule et un chiffre.';
    }
    if ($password !== $confirmation) $errors[] = 'Les mots de passe ne correspondent pas.';

    if (!$errors) {
        $check = db()->prepare('SELECT id FROM utilisateurs WHERE email = ?');
        $check->execute([strtolower($values['email'])]);
        if ($check->fetch()) $errors[] = 'Cette adresse email est déjà utilisée.';
        else {
            $stmt = db()->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)');
            $stmt->execute([$values['nom'], $values['prenom'], strtolower($values['email']), password_hash($password, PASSWORD_DEFAULT)]);
            redirect('login.php?registered=1');
        }
    }
}
$pageTitle = 'Inscription';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout"><div class="auth-card wide">
    <p class="eyebrow">NOUVEAU COMPTE</p><h1>Créer un compte</h1>
    <?php if ($errors): ?><div class="alert alert-error"><?= e($errors[0]) ?></div><?php endif; ?>
    <form method="post" data-auth-form>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="form-grid">
            <div><label for="prenom">Prénom</label><input id="prenom" name="prenom" value="<?= e($values['prenom']) ?>" required></div>
            <div><label for="nom">Nom</label><input id="nom" name="nom" value="<?= e($values['nom']) ?>" required></div>
        </div>
        <label for="email">Adresse email</label><input id="email" name="email" type="email" value="<?= e($values['email']) ?>" required autocomplete="email">
        <label for="mot_de_passe">Mot de passe</label><input id="mot_de_passe" name="mot_de_passe" type="password" required minlength="8" data-password>
        <small class="password-hint">8 caractères minimum, une majuscule et un chiffre.</small>
        <label for="confirmation">Confirmer le mot de passe</label><input id="confirmation" name="confirmation" type="password" required minlength="8">
        <button class="btn btn-block" type="submit">Créer mon compte</button>
    </form>
    <p class="form-footer">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
