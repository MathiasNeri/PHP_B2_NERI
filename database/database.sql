-- Portfolio PHP/MVC - Base de données
-- Projet B2

-- FORCER L'ENCODAGE UTF-8 AU DÉBUT
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_client = utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_database = utf8mb4;
SET character_set_results = utf8mb4;
SET character_set_server = utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;
SET collation_database = utf8mb4_unicode_ci;
SET collation_server = utf8mb4_unicode_ci;

-- Création de la base de données et de l'utilisateur
CREATE DATABASE IF NOT EXISTS projetb2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Création de l'utilisateur de base de données
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';

-- Attribution des droits sur la base de données
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';

-- Application des changements
FLUSH PRIVILEGES;

USE projetb2;

-- FORCER L'ENCODAGE POUR CETTE SESSION
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_client = utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_database = utf8mb4;
SET character_set_results = utf8mb4;
SET character_set_server = utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;
SET collation_database = utf8mb4_unicode_ci;
SET collation_server = utf8mb4_unicode_ci;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    security_question VARCHAR(255) DEFAULT 'Quel est le nom de votre premier animal de compagnie ?',
    security_answer VARCHAR(255) DEFAULT 'chat',
    bio TEXT DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    profile_completed BOOLEAN DEFAULT FALSE
);

-- Table des compétences
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    category VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table de liaison utilisateurs-compétences
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    level ENUM('débutant', 'intermédiaire', 'avancé', 'expert') DEFAULT 'débutant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_skill (user_id, skill_id)
);

-- Insertion des données de test

-- Utilisateurs (mot de passe: password)
INSERT INTO users (username, email, password, role, bio, security_question, security_answer, profile_completed) VALUES
('Admin', 'Admin@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'admin', 'Administrateur principal du système. Gestion complète des utilisateurs et des compétences.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', TRUE),
('User1', 'User1@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user', 'Développeur web passionné par les nouvelles technologies.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', TRUE),
('User2', 'User2@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user', 'Designer créatif spécialisé dans l\'expérience utilisateur.', 'Quel est le nom de votre premier animal de compagnie ?', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', TRUE);

-- Compétences
INSERT INTO skills (name, description, category) VALUES
('PHP', 'Langage de programmation côté serveur', 'Langage de programmation'),
('JavaScript', 'Langage de programmation côté client', 'Langage de programmation'),
('HTML/CSS', 'Langages de balisage et de style', 'Langage de programmation'),
('Python', 'Langage de programmation polyvalent', 'Langage de programmation'),
('Java', 'Langage de programmation orienté objet', 'Langage de programmation'),
('C++', 'Langage de programmation système', 'Langage de programmation'),
('MySQL', 'Système de gestion de base de données', 'Base de données'),
('PostgreSQL', 'Système de gestion de base de données avancé', 'Base de données'),
('MongoDB', 'Base de données NoSQL', 'Base de données'),
('Git', 'Système de contrôle de version', 'Outils de développement'),
('Docker', 'Plateforme de conteneurisation', 'DevOps'),
('Linux', 'Système d\'exploitation open source', 'DevOps'),
('Bootstrap', 'Framework CSS pour le design responsive', 'Framework'),
('React', 'Bibliothèque JavaScript pour les interfaces', 'Framework'),
('Vue.js', 'Framework JavaScript progressif', 'Framework'),
('Laravel', 'Framework PHP pour le développement web', 'Framework'),
('Symfony', 'Framework PHP pour applications web', 'Framework'),
('Node.js', 'Environnement JavaScript côté serveur', 'Framework'),
('Photoshop', 'Logiciel de retouche d\'image', 'Design'),
('Figma', 'Outil de design collaboratif', 'Design'),
('Adobe XD', 'Outil de design d\'interface', 'Design'),
('WordPress', 'Système de gestion de contenu', 'Outils de développement'),
('VS Code', 'Éditeur de code source', 'Outils de développement'),
('PhpStorm', 'IDE pour le développement PHP', 'Outils de développement'),
('GitHub', 'Plateforme d\'hébergement de code', 'Outils de développement'),
('AWS', 'Services cloud d\'Amazon', 'DevOps'),
('Azure', 'Services cloud de Microsoft', 'DevOps'),
('Google Cloud', 'Services cloud de Google', 'DevOps');

-- Projets avec encodage UTF-8 correct et images existantes
INSERT INTO projects (user_id, title, description, image, link) VALUES
-- Projets de Admin
(1, 'Système de Gestion Portfolio', 'Application complète de gestion de portfolio avec authentification et interface admin.', 'profile_1_1753192519.jpg', 'https://github.com/admin/portfolio-system'),
(1, 'API REST Sécurisée', 'API RESTful avec authentification JWT et validation des données.', 'profile_6_1753191786.jpg', 'https://github.com/admin/secure-api'),
(1, 'Dashboard Analytics', 'Tableau de bord d\'analytics avec graphiques et métriques en temps réel.', '687f95a1bdb7b.jpg', 'https://github.com/admin/analytics-dashboard'),

-- Projets de User1
(2, 'Site E-commerce Moderne', 'Boutique en ligne responsive avec panier dynamique et paiement sécurisé.', 'profile_1_1753192519.jpg', 'https://github.com/user1/modern-ecommerce'),
(2, 'Application de Blog', 'Blog personnel avec système de commentaires et gestion des articles.', 'profile_6_1753191786.jpg', 'https://github.com/user1/blog-application'),
(2, 'Portfolio Développeur', 'Portfolio professionnel avec animations et design moderne.', '687f95a1bdb7b.jpg', 'https://github.com/user1/dev-portfolio'),

-- Projets de User2
(3, 'Design System Complet', 'Système de design avec composants réutilisables et documentation.', 'profile_1_1753192519.jpg', 'https://github.com/user2/design-system'),
(3, 'Application Mobile UI', 'Interface utilisateur mobile avec animations fluides et design intuitif.', 'profile_6_1753191786.jpg', 'https://github.com/user2/mobile-ui'),
(3, 'Site Vitrine Créatif', 'Site vitrine avec design créatif et expérience utilisateur optimisée.', '687f95a1bdb7b.jpg', 'https://github.com/user2/creative-showcase');

-- Compétences des utilisateurs
INSERT INTO user_skills (user_id, skill_id, level) VALUES
-- Admin (5 compétences)
(1, 1, 'expert'),   -- PHP
(1, 7, 'expert'),   -- MySQL
(1, 10, 'expert'),  -- Git
(1, 16, 'avancé'),  -- Laravel
(1, 21, 'avancé'),  -- VS Code

-- User1 (4 compétences)
(2, 1, 'avancé'),   -- PHP
(2, 2, 'avancé'),   -- JavaScript
(2, 3, 'expert'),   -- HTML/CSS
(2, 13, 'intermédiaire'), -- Bootstrap

-- User2 (4 compétences)
(3, 2, 'expert'),   -- JavaScript
(3, 3, 'expert'),   -- HTML/CSS
(3, 19, 'expert'),  -- Photoshop
(3, 20, 'avancé');  -- Figma

-- Index pour optimiser les performances
CREATE INDEX idx_projects_user ON projects(user_id);
CREATE INDEX idx_user_skills_user ON user_skills(user_id);
CREATE INDEX idx_user_skills_skill ON user_skills(skill_id);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username); 