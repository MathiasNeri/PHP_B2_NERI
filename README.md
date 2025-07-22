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

   **Option A - Ligne de commande (recommandé) :**
   ```bash
   # Se connecter à MySQL en tant qu'administrateur
   mysql -u root -p
   
   # Une fois connecté, exécuter :
   source database/database.sql;
   
   # Ou directement en une seule commande :
   mysql -u root -p < database/database.sql
   ```

   **Option B - phpMyAdmin :**
   - Ouvrir phpMyAdmin
   - Cliquer sur "Importer"
   - Sélectionner le fichier `database/database.sql`
   - Cliquer sur "Exécuter"

   Le script créera automatiquement :
   - La base de données `projetb2`
   - L'utilisateur MySQL `projetb2` avec mot de passe `password`
   - Toutes les tables nécessaires
   - Les 3 comptes de test avec leurs projets et compétences

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

### Dépannage

#### Erreur de connexion à la base de données
Si vous obtenez une erreur "Accès refusé pour l'utilisateur 'projetb2'", vérifiez que :
- Le script `database.sql` a bien été exécuté complètement
- L'utilisateur `projetb2` existe dans MySQL
- Les privilèges ont été accordés

Pour vérifier/créer manuellement l'utilisateur :
```sql
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';
FLUSH PRIVILEGES;
```

#### Erreur "database not found"
Assurez-vous que la base de données `projetb2` existe :
```sql
SHOW DATABASES;
USE projetb2;
SHOW TABLES;
```

### Comptes de Test

#### 👑 Compte Administrateur
- **Nom** : Admin
- **Email** : Admin@example.com
- **Mot de passe** : password

#### 👤 Comptes Utilisateurs
- **Nom** : User1
- **Email** : User1@example.com
- **Mot de passe** : password

- **Nom** : User2
- **Email** : User2@example.com
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
Une question ou un bug ? Contactez-moi : [mathias.neri@ynov.com] 