-- Script pour recréer les données avec le bon encodage UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Supprimer toutes les données existantes des projets
DELETE FROM projects;

-- Réinitialiser l'auto-increment
ALTER TABLE projects AUTO_INCREMENT = 1;

-- Insérer les projets avec le bon encodage UTF-8
INSERT INTO projects (user_id, title, description, image, link) VALUES
-- Projets de Admin
(1, 'Système de Gestion Portfolio', 'Application complète de gestion de portfolio avec authentification et interface admin.', NULL, 'https://github.com/admin/portfolio-system'),
(1, 'API REST Sécurisée', 'API RESTful avec authentification JWT et validation des données.', NULL, 'https://github.com/admin/secure-api'),
(1, 'Dashboard Analytics', 'Tableau de bord d''analytics avec graphiques et métriques en temps réel.', NULL, 'https://github.com/admin/analytics-dashboard'),

-- Projets de User1
(2, 'Site E-commerce Moderne', 'Boutique en ligne responsive avec panier dynamique et paiement sécurisé.', NULL, 'https://github.com/user1/modern-ecommerce'),
(2, 'Application de Blog', 'Blog personnel avec système de commentaires et gestion des articles.', NULL, 'https://github.com/user1/blog-application'),
(2, 'Portfolio Développeur', 'Portfolio professionnel avec animations et design moderne.', NULL, 'https://github.com/user1/dev-portfolio'),

-- Projets de User2
(3, 'Design System Complet', 'Système de design avec composants réutilisables et documentation.', NULL, 'https://github.com/user2/design-system'),
(3, 'Application Mobile UI', 'Interface utilisateur mobile avec animations fluides et design intuitif.', NULL, 'https://github.com/user2/mobile-ui'),
(3, 'Site Vitrine Créatif', 'Site vitrine avec design créatif et expérience utilisateur optimisée.', NULL, 'https://github.com/user2/creative-showcase'); 