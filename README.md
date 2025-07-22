# Projet Portfolio - Gestion des Utilisateurs et des Compétences

## 📋 Présentation du Projet

Ce projet est une application web développée en PHP & MySQL permettant aux utilisateurs de :
- ✅ Gérer leur profil (inscription, connexion, mise à jour des informations)
- ✅ Ajouter et modifier leurs compétences parmi celles définies par un administrateur
- ✅ Ajouter et gérer leurs projets (titre, description, image et lien)
- ✅ Un administrateur peut gérer les compétences disponibles

## 🚀 Fonctionnalités Implémentées

### 🔐 Authentification & Gestion des Comptes
- ✅ Inscription avec validation des champs
- ✅ Connexion sécurisée avec sessions et option "Se souvenir de moi"
- ✅ Gestion des rôles (Admin / Utilisateur)
- ✅ Mise à jour des informations utilisateur
- ✅ Réinitialisation du mot de passe
- ✅ Déconnexion sécurisée

### 💡 Gestion des Compétences
- ✅ L'administrateur peut gérer les compétences proposées
- ✅ Un utilisateur peut sélectionner ses compétences parmi celles disponibles
- ✅ Niveau de compétence défini sur une échelle (débutant → expert)

### 📁 Gestion des Projets
- ✅ Ajout, modification et suppression de projets
- ✅ Chaque projet contient : Titre, Description, Image, Lien externe
- ✅ Upload sécurisé des images avec restrictions de format et taille
- ✅ Affichage structuré des projets

### 🛡️ Sécurité
- ✅ Protection contre XSS, CSRF et injections SQL
- ✅ Hachage sécurisé des mots de passe
- ✅ Gestion des erreurs utilisateur avec affichage des messages et conservation des champs remplis
- ✅ Expiration automatique de la session après inactivité

## ⚙️ Installation et Configuration

### 📋 Prérequis
- Serveur local (XAMPP, WAMP, MAMP, etc.)
- PHP 8.x et MySQL 5.7+
- Un navigateur moderne

### 🔧 Prérequis PATH Windows
Pour que les commandes fonctionnent dans PowerShell, ajoutez ces chemins à votre PATH :

**Chemins nécessaires :**
- `C:\wamp64\bin\php\php8.3.6` (ou votre version PHP)
- `C:\wamp64\bin\mysql\mysql8.3.0\bin` (ou votre version MySQL)

**Vérification :**
```powershell
php --version
mysql --version
```

**Si les commandes ne fonctionnent pas :**
- Utilisez les chemins complets : `C:\wamp64\bin\php\php8.3.6\php.exe`
- Ou ajoutez les chemins au PATH Windows

### 🚀 Installation Automatisée (Recommandée)

#### Méthode 1 : Installation rapide
```bash
# Cloner le projet
git clone url_de_votre_repo
cd PHP_B2_NERI

# Lancer l'installation automatique
php install_simple.php
```

#### Méthode 2 : Installation complète
```bash
# Cloner le projet
git clone url_de_votre_repo
cd PHP_B2_NERI

# Lancer l'installation complète
php install.php
```

**Avantages de l'installation automatisée :**
- ✅ Configuration automatique de l'encodage UTF-8
- ✅ Création automatique de la base de données et des utilisateurs
- ✅ Insertion des données de test avec le bon encodage
- ✅ Vérification des prérequis système
- ✅ Création automatique des dossiers nécessaires

### 🔧 Installation Manuelle

#### Étape 1 : Cloner le projet
```bash
git clone url_de_votre_repo
cd PHP_B2_NERI
```

#### Étape 2 : Importer la base de données

**Méthode A - Ligne de commande (Recommandée)**

**Sur Windows PowerShell :**
```powershell
Get-Content database/database.sql | mysql -u root -p
```

**Sur Linux/Mac ou Git Bash :**
```bash
mysql -u root -p < database/database.sql
```

**Méthode B - phpMyAdmin :**
1. Ouvrir phpMyAdmin dans votre navigateur
2. Créer une nouvelle base de données nommée `projetb2`
3. Sélectionner la base de données `projetb2`
4. Cliquer sur l'onglet "Importer"
5. Sélectionner le fichier `database/database.sql`
6. Cliquer sur "Exécuter"

#### Étape 3 : Vérifier l'importation
```sql
-- Vérifier que la base de données existe
SHOW DATABASES;

-- Vérifier que les tables sont créées
USE projetb2;
SHOW TABLES;
```

#### Étape 4 : Configurer la connexion à la base de données
Modifier le fichier `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'projetb2');
define('DB_USER', 'projetb2');
define('DB_PASS', 'password');
define('DB_PORT', 3306);
```

#### Étape 5 : Démarrer l'application
```bash
# Démarrer le serveur PHP intégré
php -S localhost:8000

# Ou utiliser votre serveur local (XAMPP, WAMP, etc.)
# Puis accéder à : http://localhost/votre_projet
```

#### Étape 6 : Accéder à l'application
Ouvrir votre navigateur et aller sur : `http://localhost:8000`

**✅ Installation terminée ! Vous pouvez maintenant vous connecter avec les comptes de test.**

## 🔑 Comptes de Test

### 👑 Compte Administrateur
- **Nom d'utilisateur :** Admin
- **Email :** Admin@example.com
- **Mot de passe :** password

### 👤 Comptes Utilisateurs
- **Nom d'utilisateur :** User1
- **Email :** User1@example.com
- **Mot de passe :** password

- **Nom d'utilisateur :** User2
- **Email :** User2@example.com
- **Mot de passe :** password

## 🆘 Dépannage

### 🆘 Problèmes courants lors de l'installation

#### ❌ Erreur "php n'est pas reconnu"
Si vous obtenez l'erreur "php n'est pas reconnu comme nom d'applet de commande" :

**Solution 1 - Ajouter PHP au PATH Windows :**

**Pour WAMP64 :**
```powershell
# Ajouter temporairement pour cette session
$env:PATH += ";C:\wamp64\bin\php\php8.3.6"

# Ajouter définitivement (redémarrer PowerShell après)
[Environment]::SetEnvironmentVariable("PATH", $env:PATH + ";C:\wamp64\bin\php\php8.3.6", "User")
```

**Pour XAMPP :**
```powershell
# Ajouter temporairement pour cette session
$env:PATH += ";C:\xampp\php"

# Ajouter définitivement (redémarrer PowerShell après)
[Environment]::SetEnvironmentVariable("PATH", $env:PATH + ";C:\xampp\php", "User")
```

**Vérification :**
```powershell
php --version
```

**Solution 2 - Utiliser le chemin complet :**
```powershell
# Pour WAMP64
C:\wamp64\bin\php\php8.3.6\php.exe -S localhost:8000

# Pour XAMPP
C:\xampp\php\php.exe -S localhost:8000
```

**Solution 3 - Utiliser un serveur local :**
- Installer XAMPP, WAMP ou MAMP
- Copier le projet dans le dossier `htdocs`
- Démarrer Apache et MySQL
- Accéder via `http://localhost/PHP_B2_NERI`

### ❌ Erreur de connexion à la base de données
Si vous obtenez une erreur "Accès refusé pour l'utilisateur 'projetb2'" :

1. **Vérifier que le script a été exécuté :**
```sql
SHOW DATABASES;
USE projetb2;
SHOW TABLES;
```

2. **Créer manuellement l'utilisateur si nécessaire :**
```sql
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';
FLUSH PRIVILEGES;
```

### ❌ Erreur "Base de données non trouvée"
Assurez-vous que la base de données `projetb2` existe :
```sql
SHOW DATABASES;
```

### ❌ Erreur "Tables non trouvées"
Vérifiez que les tables ont été créées :
```sql
USE projetb2;
SHOW TABLES;
```

## 📁 Structure du Projet

```
PHP_B2_NERI/
├── config/
│   └── database.php          # Configuration de la base de données
├── models/                   # Classes PHP (User, Project, Skill)
├── controllers/              # Gestion des requêtes et logiques métier
├── views/                    # Interfaces utilisateur (HTML, CSS, Bootstrap)
├── public/                   # Images et assets du projet
├── database/
│   └── database.sql          # Script SQL pour initialiser la base de données
└── README.md                 # Documentation du projet
```

## 🛠️ Technologies Utilisées

- **Backend :** PHP 8.x, MySQL 5.7+
- **Frontend :** HTML5, CSS3, Bootstrap 5, JavaScript (Vanilla)
- **Sécurité :** password_hash(), CSRF tokens, PDO avec prepared statements
- **Gestion du Projet :** Git, GitHub

## 📄 Licence

Ce projet est sous licence MIT.

## 📧 Contact

Une question ou un bug ? Contactez-moi : [mathias.neri@ynov.com] 