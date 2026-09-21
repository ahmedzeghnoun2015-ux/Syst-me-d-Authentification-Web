# Project — authentification PHP

Application PHP/MySQL prête pour WAMP ou XAMPP : inscription, connexion, déconnexion, rôles `USER`/`ADMIN` et réinitialisation de mot de passe.

## Installation locale

1. Copiez le dossier dans `C:\wamp64\www\Project` (ou `C:\xampp\htdocs\Project`).
2. Démarrez Apache et MySQL.
3. Importez [`database/project.sql`](database/project.sql) dans phpMyAdmin.
4. Vérifiez les constantes de connexion dans [`config/config.php`](config/config.php).
5. Ouvrez `http://localhost/Project/`.

Pour tester l’espace administrateur, créez un compte via l’inscription puis modifiez sa colonne `role` en `ADMIN` dans phpMyAdmin.

## Réinitialisation en local

L’envoi email est simulé : après avoir saisi une adresse existante dans `forgot-password.php`, le lien apparaît à l’écran. En production, remplacez cet affichage par un envoi SMTP et conservez le hash du token en base.

## Captures d’écran pour la remise GitHub

Avec l’application lancée, prenez des captures de l’accueil, de l’inscription, de la connexion, du lien de récupération simulé, du tableau de bord utilisateur et du tableau de bord administrateur. Ajoutez-les dans un dossier `screenshots/` avant de publier le dépôt.
