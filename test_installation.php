<?php
/**
 * Script de test de l'installation
 * Portfolio PHP/MVC - Projet B2
 * 
 * Ce script vérifie que l'installation s'est bien déroulée
 */

echo "=== Test de l'installation du Portfolio PHP/MVC ===\n\n";

// 1. Test de la configuration
echo "1. Test de la configuration...\n";
if (file_exists('config/database.php')) {
    echo "✅ Fichier de configuration trouvé\n";
} else {
    echo "❌ Fichier de configuration manquant\n";
    exit(1);
}

// 2. Test de la connexion à la base de données
echo "2. Test de la connexion à la base de données...\n";
try {
    require_once 'config/database.php';
    $pdo = getDatabaseConnection();
    echo "✅ Connexion à la base de données réussie\n";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Test de l'encodage UTF-8
echo "3. Test de l'encodage UTF-8...\n";
try {
    $stmt = $pdo->query("SELECT title FROM projects LIMIT 1");
    $project = $stmt->fetch();
    
    if ($project && mb_check_encoding($project['title'], 'UTF-8')) {
        echo "✅ Encodage UTF-8 correct : " . $project['title'] . "\n";
    } else {
        echo "❌ Problème d'encodage UTF-8 détecté\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors du test d'encodage : " . $e->getMessage() . "\n";
}

// 4. Test des données
echo "4. Test des données...\n";
try {
    // Compter les utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "✅ Utilisateurs : $userCount\n";
    
    // Compter les compétences
    $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
    $skillCount = $stmt->fetchColumn();
    echo "✅ Compétences : $skillCount\n";
    
    // Compter les projets
    $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
    $projectCount = $stmt->fetchColumn();
    echo "✅ Projets : $projectCount\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test des données : " . $e->getMessage() . "\n";
}

// 5. Test des dossiers
echo "5. Test des dossiers...\n";
if (is_dir('public/uploads')) {
    echo "✅ Dossier uploads existe\n";
} else {
    echo "❌ Dossier uploads manquant\n";
}

if (is_writable('public/uploads')) {
    echo "✅ Dossier uploads accessible en écriture\n";
} else {
    echo "❌ Dossier uploads non accessible en écriture\n";
}

// 6. Test des extensions PHP
echo "6. Test des extensions PHP...\n";
$requiredExtensions = ['pdo_mysql', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extension $ext chargée\n";
    } else {
        echo "❌ Extension $ext manquante\n";
    }
}

// 7. Test de l'application
echo "7. Test de l'application...\n";
if (file_exists('index.php')) {
    echo "✅ Fichier index.php trouvé\n";
} else {
    echo "❌ Fichier index.php manquant\n";
}

echo "\n=== Résumé du test ===\n";
echo "🎉 L'installation semble correcte !\n";
echo "🔗 Vous pouvez maintenant accéder à l'application : http://localhost:8000\n";
echo "👤 Comptes de test disponibles :\n";
echo "   - Admin : Admin@example.com / password\n";
echo "   - User1 : User1@example.com / password\n";
echo "   - User2 : User2@example.com / password\n";
?> 