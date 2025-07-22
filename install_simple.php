<?php
/**
 * Script d'installation simplifié
 * Portfolio PHP/MVC - Projet B2
 * 
 * Utilisez ce script pour une installation rapide
 */

// Charger la configuration
$config = require_once 'config/install_config.php';

echo "=== Installation rapide du Portfolio PHP/MVC ===\n\n";

// Vérifier les prérequis
echo "🔍 Vérification des prérequis...\n";
$errors = [];

// Vérifier PHP
if (version_compare(PHP_VERSION, $config['requirements']['php_version'], '<')) {
    $errors[] = "PHP " . $config['requirements']['php_version'] . " ou supérieur requis (actuel: " . PHP_VERSION . ")";
}

// Vérifier les extensions
foreach ($config['requirements']['php_extensions'] as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Extension PHP '$ext' manquante";
    }
}

if (!empty($errors)) {
    echo "❌ Erreurs détectées :\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\nVeuillez installer les prérequis manquants.\n";
    exit(1);
}

echo "✅ Prérequis OK\n\n";

// Connexion MySQL
echo "🔌 Connexion à MySQL...\n";
try {
    $pdo = new PDO("mysql:host={$config['database']['host']};port={$config['database']['port']}", 
                   $config['database']['root_user'], 
                   $config['database']['root_pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion MySQL réussie\n";
} catch (PDOException $e) {
    die("❌ Erreur de connexion MySQL : " . $e->getMessage() . "\n");
}

// Créer la base de données
echo "🗄️  Création de la base de données...\n";
try {
    $pdo->exec("DROP DATABASE IF EXISTS {$config['database']['name']}");
    $pdo->exec("CREATE DATABASE {$config['database']['name']} CHARACTER SET {$config['encoding']['charset']} COLLATE {$config['encoding']['collation']}");
    echo "✅ Base de données créée avec encodage UTF-8\n";
} catch (PDOException $e) {
    die("❌ Erreur création base : " . $e->getMessage() . "\n");
}

// Créer l'utilisateur
echo "👤 Création de l'utilisateur...\n";
try {
    $pdo->exec("DROP USER IF EXISTS '{$config['database']['user']}'@'localhost'");
    $pdo->exec("CREATE USER '{$config['database']['user']}'@'localhost' IDENTIFIED BY '{$config['database']['pass']}'");
    $pdo->exec("GRANT ALL PRIVILEGES ON {$config['database']['name']}.* TO '{$config['database']['user']}'@'localhost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "✅ Utilisateur créé\n";
} catch (PDOException $e) {
    echo "⚠️  Utilisateur peut-être déjà existant\n";
}

// Se connecter à la nouvelle base
echo "🔗 Connexion à la base...\n";
try {
    $pdo = new PDO("mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['name']};charset={$config['encoding']['charset']}", 
                   $config['database']['user'], 
                   $config['database']['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES {$config['encoding']['charset']} COLLATE {$config['encoding']['collation']}");
    echo "✅ Connexion réussie\n";
} catch (PDOException $e) {
    die("❌ Erreur connexion : " . $e->getMessage() . "\n");
}

// Créer les tables
echo "📋 Création des tables...\n";
$sql = file_get_contents('database/database.sql');
if ($sql === false) {
    die("❌ Impossible de lire database.sql\n");
}

$lines = explode(';', $sql);
foreach ($lines as $line) {
    $line = trim($line);
    if (!empty($line) && !preg_match('/^(--|#|\/\*)/', $line)) {
        try {
            $pdo->exec($line);
        } catch (PDOException $e) {
            // Ignorer les erreurs de création d'utilisateur
            if (!strpos($e->getMessage(), 'already exists')) {
                echo "⚠️  " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "✅ Tables créées\n";

// Insérer les données de test
echo "📝 Insertion des données de test...\n";
try {
    // Nettoyer les tables
    $pdo->exec("DELETE FROM projects");
    $pdo->exec("DELETE FROM user_skills");
    $pdo->exec("DELETE FROM skills");
    $pdo->exec("DELETE FROM users");
    
    // Réinitialiser les auto-increment
    $pdo->exec("ALTER TABLE projects AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE user_skills AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE skills AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    
    // Insérer les utilisateurs
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, bio, security_question, security_answer, profile_completed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($config['test_accounts'] as $user) {
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT, ['cost' => $config['security']['password_cost']]);
        $stmt->execute([
            $user['username'],
            $user['email'],
            $hashedPassword,
            $user['role'],
            $user['bio'],
            'Quel est le nom de votre premier animal de compagnie ?',
            $hashedPassword,
            true
        ]);
    }
    
    // Insérer les compétences (données simplifiées)
    $stmt = $pdo->prepare("INSERT INTO skills (name, description, category, is_public) VALUES (?, ?, ?, ?)");
    
    $skills = [
        ['PHP', 'Langage de programmation côté serveur', 'Langage de programmation', true],
        ['JavaScript', 'Langage de programmation côté client', 'Langage de programmation', true],
        ['HTML/CSS', 'Langages de balisage et de style', 'Langage de programmation', true],
        ['MySQL', 'Système de gestion de base de données', 'Base de données', true],
        ['Git', 'Système de contrôle de version', 'Outils de développement', true],
        ['Bootstrap', 'Framework CSS pour le design responsive', 'Framework', true],
        ['Laravel', 'Framework PHP pour le développement web', 'Framework', true],
        ['VS Code', 'Éditeur de code source', 'Outils de développement', true],
        ['Photoshop', 'Logiciel de retouche d\'image', 'Design', true],
        ['Figma', 'Outil de design collaboratif', 'Design', true]
    ];
    
    foreach ($skills as $skill) {
        $stmt->execute($skill);
    }
    
    // Insérer quelques projets
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, description, image, link) VALUES (?, ?, ?, ?, ?)");
    
    $projects = [
        [1, 'Système de Gestion Portfolio', 'Application complète de gestion de portfolio avec authentification et interface admin.', null, 'https://github.com/admin/portfolio-system'],
        [2, 'Site E-commerce Moderne', 'Boutique en ligne responsive avec panier dynamique et paiement sécurisé.', null, 'https://github.com/user1/modern-ecommerce'],
        [3, 'Design System Complet', 'Système de design avec composants réutilisables et documentation.', null, 'https://github.com/user2/design-system']
    ];
    
    foreach ($projects as $project) {
        $stmt->execute($project);
    }
    
    // Insérer quelques compétences utilisateurs
    $stmt = $pdo->prepare("INSERT INTO user_skills (user_id, skill_id, level) VALUES (?, ?, ?)");
    
    $userSkills = [
        [1, 1, 'expert'],   // Admin - PHP
        [1, 4, 'expert'],   // Admin - MySQL
        [1, 5, 'expert'],   // Admin - Git
        [2, 1, 'avancé'],   // User1 - PHP
        [2, 2, 'avancé'],   // User1 - JavaScript
        [2, 3, 'expert'],   // User1 - HTML/CSS
        [3, 2, 'expert'],   // User2 - JavaScript
        [3, 9, 'expert'],   // User2 - Photoshop
        [3, 10, 'avancé']   // User2 - Figma
    ];
    
    foreach ($userSkills as $userSkill) {
        $stmt->execute($userSkill);
    }
    
    echo "✅ Données de test insérées avec encodage UTF-8 correct\n";
} catch (PDOException $e) {
    die("❌ Erreur insertion données : " . $e->getMessage() . "\n");
}

// Créer le dossier uploads
echo "📁 Création du dossier uploads...\n";
if (!is_dir('public/uploads')) {
    if (mkdir('public/uploads', 0755, true)) {
        echo "✅ Dossier uploads créé\n";
    } else {
        echo "⚠️  Impossible de créer le dossier uploads\n";
    }
} else {
    echo "✅ Dossier uploads existe déjà\n";
}

echo "\n🎉 === Installation terminée avec succès ! ===\n\n";
echo "🔗 Accès à l'application : {$config['app']['url']}\n";
echo "👤 Comptes de test :\n";
foreach ($config['test_accounts'] as $account) {
    echo "   - {$account['username']} : {$account['email']} / {$account['password']}\n";
}
echo "\n📝 Note : Tous les caractères accentués sont correctement encodés en UTF-8.\n";
echo "🔧 Pour personnaliser la configuration, modifiez config/install_config.php\n";
?> 