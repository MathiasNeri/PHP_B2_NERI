-- Script de correction de l'encodage UTF-8
-- Forcer l'encodage UTF-8 pour cette session
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Convertir la base de données en UTF-8
ALTER DATABASE projetb2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Convertir toutes les tables en UTF-8
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE skills CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE projects CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE user_skills CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Mettre à jour les données des projets avec l'encodage correct
UPDATE projects SET 
    title = 'Système de Gestion Portfolio',
    description = 'Application complète de gestion de portfolio avec authentification et interface admin.'
WHERE id = 1;

UPDATE projects SET 
    title = 'API REST Sécurisée',
    description = 'API RESTful avec authentification JWT et validation des données.'
WHERE id = 2;

UPDATE projects SET 
    title = 'Dashboard Analytics',
    description = 'Tableau de bord d\'analytics avec graphiques et métriques en temps réel.'
WHERE id = 3;

UPDATE projects SET 
    title = 'Site E-commerce Moderne',
    description = 'Boutique en ligne responsive avec panier dynamique et paiement sécurisé.'
WHERE id = 4;

UPDATE projects SET 
    title = 'Application de Blog',
    description = 'Blog personnel avec système de commentaires et gestion des articles.'
WHERE id = 5;

UPDATE projects SET 
    title = 'Portfolio Développeur',
    description = 'Portfolio professionnel avec animations et design moderne.'
WHERE id = 6;

UPDATE projects SET 
    title = 'Design System Complet',
    description = 'Système de design avec composants réutilisables et documentation.'
WHERE id = 7;

UPDATE projects SET 
    title = 'Application Mobile UI',
    description = 'Interface utilisateur mobile avec animations fluides et design intuitif.'
WHERE id = 8;

UPDATE projects SET 
    title = 'Site Vitrine Créatif',
    description = 'Site vitrine avec design créatif et expérience utilisateur optimisée.'
WHERE id = 9; 