<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Accueil';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">ESPACE UTILISATEUR</p>
        <h1>Un accès simple et sécurisé à votre projet.</h1>
        <p class="lead">Connectez-vous, gérez votre profil et retrouvez vos outils dans une interface claire.</p>
        <div class="actions">
            <a class="btn" href="register.php">Commencer</a>
            <a class="btn btn-ghost" href="login.php">Se connecter</a>
        </div>
    </div>
    <div class="hero-card">
        <div class="status-dot"></div>
        <p class="muted">SÉCURITÉ</p>
        <h2>Vos données sont protégées</h2>
        <p>Mots de passe hachés, sessions sécurisées et récupération par token à durée limitée.</p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
