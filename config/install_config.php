<?php
/**
 * Configuration d'installation
 * Portfolio PHP/MVC - Projet B2
 * 
 * Modifiez ces paramètres selon votre environnement
 */

return [
    // Configuration de la base de données
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'projetb2',
        'user' => 'projetb2',
        'pass' => 'password',
        'root_user' => 'root',     // Utilisateur root MySQL (généralement 'root')
        'root_pass' => '',         // Mot de passe root MySQL (laissez vide si pas de mot de passe)
    ],
    
    // Configuration de l'application
    'app' => [
        'name' => 'Portfolio PHP/MVC',
        'url' => 'http://localhost:8000',
        'upload_path' => __DIR__ . '/../public/uploads/',
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
    ],
    
    // Configuration des sessions
    'session' => [
        'lifetime' => 600, // 10 minutes
        'cookie_lifetime' => 30 * 24 * 60 * 60, // 30 jours
    ],
    
    // Configuration de sécurité
    'security' => [
        'csrf_token_name' => 'csrf_token',
        'password_cost' => 12, // Coût pour password_hash()
    ],
    
    // Configuration de l'encodage
    'encoding' => [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'force_utf8' => true,
    ],
    
    // Comptes de test par défaut
    'test_accounts' => [
        [
            'username' => 'Admin',
            'email' => 'Admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'bio' => 'Administrateur principal du système. Gestion complète des utilisateurs et des compétences.',
        ],
        [
            'username' => 'User1',
            'email' => 'User1@example.com',
            'password' => 'password',
            'role' => 'user',
            'bio' => 'Développeur web passionné par les nouvelles technologies.',
        ],
        [
            'username' => 'User2',
            'email' => 'User2@example.com',
            'password' => 'password',
            'role' => 'user',
            'bio' => 'Designer créatif spécialisé dans l\'expérience utilisateur.',
        ],
    ],
    
    // Prérequis système
    'requirements' => [
        'php_extensions' => ['pdo_mysql', 'mbstring'],
        'php_version' => '7.4.0',
        'mysql_version' => '5.7.0',
    ],
];
?> 