# Projet Portfolio - Gestion des Utilisateurs et des Compétences

## Présentation du Projet
Ce projet est une application web développée en PHP & MySQL permettant aux utilisateurs de :
- [x] Gérer leur profil (inscription, connexion, mise à jour des informations).
- [x] Ajouter et modifier leurs compétences parmi celles définies par un administrateur.
- [x] Ajouter et gérer leurs projets (titre, description, image et lien).
- [x] Un administrateur peut gérer les compétences disponibles.

## Fonctionnalités Implémentées

### Authentification & Gestion des Comptes
- [x] Inscription avec validation des champs
- [x] Connexion sécurisée avec sessions et option "Se souvenir de moi"
- [x] Gestion des rôles (Admin / Utilisateur)
- [x] Mise à jour des informations utilisateur
- [x] Réinitialisation du mot de passe
- [x] Déconnexion sécurisée

### Gestion des Compétences
- [x] L'administrateur peut gérer les compétences proposées
- [x] Un utilisateur peut sélectionner ses compétences parmi celles disponibles
- [x] Niveau de compétence défini sur une échelle (débutant → expert)

### Gestion des Projets
- [x] Ajout, modification et suppression de projets
- [x] Chaque projet contient : Titre, Description, Image, Lien externe
- [x] Upload sécurisé des images avec restrictions de format et taille
- [x] Affichage structuré des projets

### Sécurité
- [x] Protection contre XSS, CSRF et injections SQL
- [x] Hachage sécurisé des mots de passe
- [x] Gestion des erreurs utilisateur avec affichage des messages et conservation des champs remplis
- [x] Expiration automatique de la session après inactivité

## Installation et Configuration

### Prérequis
- Serveur local (XAMPP, WAMP, etc.)
- PHP 8.x et MySQL
- Un navigateur moderne

### Étapes d'Installation
1. Cloner le projet sur votre serveur local :
   ```bash
   git clone url_de_votre_repo
   cd Php_B2
   ```

2. Importer la base de données :
   - Ouvrir phpMyAdmin ou utiliser la ligne de commande
   - Exécuter le fichier `database/database.sql`
   - Le script créera automatiquement la base de données et les tables

3. Configurer la connexion à la base de données :
   Modifier le fichier config/database.php :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'projetb2');
   define('DB_USER', 'projetb2');
   define('DB_PASS', 'password');
   define('DB_PORT', 3306);
   ```

4. Démarrer le serveur PHP et tester l'application :
   ```bash
   php -S localhost:8000
   ```
   Puis accéder à l'application via http://localhost:8000

## Comptes de Test

### Compte Administrateur
- **Email** : admin@portfolio.com
- **Mot de passe** : password

### Compte Utilisateur
- **Email** : Mat_3.user@example.com
- **Mot de passe** : password

## Structure du Projet

/config/database.php -> Configuration de la base de données
/models/         -> Classes PHP (User, Project, Skill)
/controllers/    -> Gestion des requêtes et logiques métier
/views/          -> Interfaces utilisateur (HTML, CSS, Bootstrap)
/public/         -> Images et assets du projet
/database.sql    -> Script SQL pour initialiser la base de données

## Technologies Utilisées
- **Backend** : PHP 8.x, MySQL 5.7+
- **Frontend** : HTML5, CSS3, Bootstrap 5, JavaScript (Vanilla)
- **Sécurité** : password_hash(), CSRF tokens, PDO avec prepared statements
- **Gestion du Projet** : Git, GitHub

## Licence
Ce projet est sous licence MIT.

## Contact
Une question ou un bug ? Contactez-moi : [votre.email@example.com] 