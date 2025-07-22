<?php
/**
 * Script d'installation automatique
 * Portfolio PHP/MVC - Projet B2
 * 
 * Ce script configure automatiquement :
 * - La base de données avec le bon encodage UTF-8
 * - Les données de test avec l'encodage correct
 * - La configuration de l'application
 */

// Configuration
$config = [
    'db_host' => 'localhost',
    'db_port' => 3306,
    'db_name' => 'projetb2',
    'db_user' => 'projetb2',
    'db_pass' => 'password',
    'app_name' => 'Portfolio PHP/MVC'
];

echo "=== Installation du Portfolio PHP/MVC ===\n\n";

// 1. Vérifier les prérequis
echo "1. Vérification des prérequis...\n";
if (!extension_loaded('pdo_mysql')) {
    die("❌ Erreur : L'extension PDO MySQL n'est pas installée.\n");
}
if (!extension_loaded('mbstring')) {
    die("❌ Erreur : L'extension mbstring n'est pas installée.\n");
}
echo "✅ Prérequis OK\n\n";

// 2. Connexion à MySQL
echo "2. Connexion à MySQL...\n";
try {
    $pdo = new PDO("mysql:host={$config['db_host']};port={$config['db_port']}", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion MySQL réussie\n";
} catch (PDOException $e) {
    die("❌ Erreur de connexion MySQL : " . $e->getMessage() . "\n");
}

// 3. Créer la base de données avec le bon encodage
echo "3. Création de la base de données...\n";
try {
    $pdo->exec("DROP DATABASE IF EXISTS {$config['db_name']}");
    $pdo->exec("CREATE DATABASE {$config['db_name']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données créée avec encodage UTF-8\n";
} catch (PDOException $e) {
    die("❌ Erreur création base de données : " . $e->getMessage() . "\n");
}

// 4. Créer l'utilisateur
echo "4. Création de l'utilisateur de base de données...\n";
try {
    $pdo->exec("DROP USER IF EXISTS '{$config['db_user']}'@'localhost'");
    $pdo->exec("CREATE USER '{$config['db_user']}'@'localhost' IDENTIFIED BY '{$config['db_pass']}'");
    $pdo->exec("GRANT ALL PRIVILEGES ON {$config['db_name']}.* TO '{$config['db_user']}'@'localhost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "✅ Utilisateur créé\n";
} catch (PDOException $e) {
    echo "⚠️  Erreur création utilisateur (peut-être déjà existant) : " . $e->getMessage() . "\n";
}

// 5. Se connecter à la nouvelle base
echo "5. Connexion à la base de données...\n";
try {
    $pdo = new PDO("mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4", 
                   $config['db_user'], $config['db_pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Connexion à la base réussie\n";
} catch (PDOException $e) {
    die("❌ Erreur connexion base : " . $e->getMessage() . "\n");
}

// 6. Créer les tables
echo "6. Création des tables...\n";
$sql = file_get_contents('database/database.sql');
if ($sql === false) {
    die("❌ Erreur : Impossible de lire le fichier database.sql\n");
}

// Exécuter le script SQL ligne par ligne
$lines = explode(';', $sql);
foreach ($lines as $line) {
    $line = trim($line);
    if (!empty($line) && !preg_match('/^(--|#|\/\*)/', $line)) {
        try {
            $pdo->exec($line);
        } catch (PDOException $e) {
            // Ignorer les erreurs de création d'utilisateur si déjà existant
            if (!strpos($e->getMessage(), 'already exists')) {
                echo "⚠️  Erreur SQL : " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "✅ Tables créées\n";

// 7. Insérer les données de test avec le bon encodage
echo "7. Insertion des données de test...\n";
try {
    // Supprimer les données existantes
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
    
    $users = [
        ['Admin', 'Admin@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'admin', 'Administrateur principal du système. Gestion complète des utilisateurs et des compétences.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', true],
        ['User1', 'User1@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user', 'Développeur web passionné par les nouvelles technologies.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', true],
        ['User2', 'User2@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user', 'Designer créatif spécialisé dans l\'expérience utilisateur.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', true]
    ];
    
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    
    // Insérer les compétences
    $stmt = $pdo->prepare("INSERT INTO skills (name, description, category, is_public) VALUES (?, ?, ?, ?)");
    
    $skills = [
        ['PHP', 'Langage de programmation côté serveur', 'Langage de programmation', true],
        ['JavaScript', 'Langage de programmation côté client', 'Langage de programmation', true],
        ['HTML/CSS', 'Langages de balisage et de style', 'Langage de programmation', true],
        ['Python', 'Langage de programmation polyvalent', 'Langage de programmation', true],
        ['Java', 'Langage de programmation orienté objet', 'Langage de programmation', true],
        ['C++', 'Langage de programmation système', 'Langage de programmation', true],
        ['MySQL', 'Système de gestion de base de données', 'Base de données', true],
        ['PostgreSQL', 'Système de gestion de base de données avancé', 'Base de données', true],
        ['MongoDB', 'Base de données NoSQL', 'Base de données', true],
        ['Git', 'Système de contrôle de version', 'Outils de développement', true],
        ['Docker', 'Plateforme de conteneurisation', 'DevOps', true],
        ['Linux', 'Système d\'exploitation open source', 'DevOps', true],
        ['Bootstrap', 'Framework CSS pour le design responsive', 'Framework', true],
        ['React', 'Bibliothèque JavaScript pour les interfaces', 'Framework', true],
        ['Vue.js', 'Framework JavaScript progressif', 'Framework', true],
        ['Laravel', 'Framework PHP pour le développement web', 'Framework', true],
        ['Symfony', 'Framework PHP pour applications web', 'Framework', true],
        ['Node.js', 'Environnement JavaScript côté serveur', 'Framework', true],
        ['Photoshop', 'Logiciel de retouche d\'image', 'Design', true],
        ['Figma', 'Outil de design collaboratif', 'Design', true],
        ['Adobe XD', 'Outil de design d\'interface', 'Design', true],
        ['WordPress', 'Système de gestion de contenu', 'Outils de développement', true],
        ['VS Code', 'Éditeur de code source', 'Outils de développement', true],
        ['PhpStorm', 'IDE pour le développement PHP', 'Outils de développement', true],
        ['GitHub', 'Plateforme d\'hébergement de code', 'Outils de développement', true],
        ['AWS', 'Services cloud d\'Amazon', 'DevOps', true],
        ['Azure', 'Services cloud de Microsoft', 'DevOps', true],
        ['Google Cloud', 'Services cloud de Google', 'DevOps', true]
    ];
    
    foreach ($skills as $skill) {
        $stmt->execute($skill);
    }
    
    // Insérer les projets
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, description, image, link) VALUES (?, ?, ?, ?, ?)");
    
    $projects = [
        [1, 'Système de Gestion Portfolio', 'Application complète de gestion de portfolio avec authentification et interface admin.', null, 'https://github.com/admin/portfolio-system'],
        [1, 'API REST Sécurisée', 'API RESTful avec authentification JWT et validation des données.', null, 'https://github.com/admin/secure-api'],
        [1, 'Dashboard Analytics', 'Tableau de bord d\'analytics avec graphiques et métriques en temps réel.', null, 'https://github.com/admin/analytics-dashboard'],
        [2, 'Site E-commerce Moderne', 'Boutique en ligne responsive avec panier dynamique et paiement sécurisé.', null, 'https://github.com/user1/modern-ecommerce'],
        [2, 'Application de Blog', 'Blog personnel avec système de commentaires et gestion des articles.', null, 'https://github.com/user1/blog-application'],
        [2, 'Portfolio Développeur', 'Portfolio professionnel avec animations et design moderne.', null, 'https://github.com/user1/dev-portfolio'],
        [3, 'Design System Complet', 'Système de design avec composants réutilisables et documentation.', null, 'https://github.com/user2/design-system'],
        [3, 'Application Mobile UI', 'Interface utilisateur mobile avec animations fluides et design intuitif.', null, 'https://github.com/user2/mobile-ui'],
        [3, 'Site Vitrine Créatif', 'Site vitrine avec design créatif et expérience utilisateur optimisée.', null, 'https://github.com/user2/creative-showcase']
    ];
    
    foreach ($projects as $project) {
        $stmt->execute($project);
    }
    
    // Insérer les compétences utilisateurs
    $stmt = $pdo->prepare("INSERT INTO user_skills (user_id, skill_id, level) VALUES (?, ?, ?)");
    
    $userSkills = [
        [1, 1, 'expert'],   // Admin - PHP
        [1, 7, 'expert'],   // Admin - MySQL
        [1, 10, 'expert'],  // Admin - Git
        [1, 16, 'avancé'],  // Admin - Laravel
        [1, 23, 'avancé'],  // Admin - VS Code
        [2, 1, 'avancé'],   // User1 - PHP
        [2, 2, 'avancé'],   // User1 - JavaScript
        [2, 3, 'expert'],   // User1 - HTML/CSS
        [2, 13, 'intermédiaire'], // User1 - Bootstrap
        [3, 2, 'expert'],   // User2 - JavaScript
        [3, 3, 'expert'],   // User2 - HTML/CSS
        [3, 19, 'expert'],  // User2 - Photoshop
        [3, 20, 'avancé']   // User2 - Figma
    ];
    
    foreach ($userSkills as $userSkill) {
        $stmt->execute($userSkill);
    }
    
    echo "✅ Données de test insérées avec encodage UTF-8 correct\n";
} catch (PDOException $e) {
    die("❌ Erreur insertion données : " . $e->getMessage() . "\n");
}

// 8. Créer le dossier uploads
echo "8. Création du dossier uploads...\n";
if (!is_dir('public/uploads')) {
    if (mkdir('public/uploads', 0755, true)) {
        echo "✅ Dossier uploads créé\n";
    } else {
        echo "⚠️  Impossible de créer le dossier uploads\n";
    }
} else {
    echo "✅ Dossier uploads existe déjà\n";
}

echo "\n=== Installation terminée avec succès ! ===\n\n";
echo "🔗 Accès à l'application : http://localhost:8000\n";
echo "👤 Comptes de test :\n";
echo "   - Admin : Admin@example.com / password\n";
echo "   - User1 : User1@example.com / password\n";
echo "   - User2 : User2@example.com / password\n\n";
echo "📝 Note : Tous les caractères accentués sont maintenant correctement encodés en UTF-8.\n";
?> 