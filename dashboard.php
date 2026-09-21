<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$pageTitle = 'Mon espace';
require __DIR__ . '/includes/header.php';
?>
<section class="dashboard-head"><div><p class="eyebrow">ESPACE UTILISATEUR</p><h1>Bonjour, <?= e(current_user()['prenom']) ?>.</h1><p class="lead">Bienvenue dans votre espace personnel.</p></div></section>
<div class="dashboard-grid"><article class="panel"><span class="panel-icon">✓</span><h2>Compte actif</h2><p>Votre compte est correctement configuré.</p></article><article class="panel"><span class="panel-icon">@</span><h2><?= e(current_user()['email']) ?></h2><p>Adresse email associée à votre compte.</p></article></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
