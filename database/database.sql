-- Portfolio PHP/MVC - Base de données
-- Projet B2

-- Création de la base de données et de l'utilisateur
CREATE DATABASE IF NOT EXISTS projetb2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Création de l'utilisateur de base de données
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';

-- Attribution des droits sur la base de données
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';

-- Application des changements
FLUSH PRIVILEGES;

USE projetb2;

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
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@portfolio.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'admin'),
('john_doe', 'john@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user'),
('jane_smith', 'jane@example.com', '$2y$12$PcNlsgQGSojus0UNZO8WVeNUJ0fITrNNCzZVC5dGc3eWB7RR5vqy.', 'user');

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

-- Projets
INSERT INTO projects (user_id, title, description, image, link) VALUES
-- Projets de l'admin
(1, 'Portfolio Personnel', 'Un portfolio moderne développé en PHP/MVC avec authentification et gestion de projets', 'portfolio.jpg', 'https://github.com/admin/portfolio'),
(1, 'Système de Gestion', 'Application web pour la gestion d\'entreprise avec interface admin', 'gestion.jpg', 'https://github.com/admin/gestion'),
(1, 'API REST', 'API RESTful développée en PHP pour une application mobile', 'api.jpg', 'https://github.com/admin/api'),

-- Projets de John Doe
(2, 'Site E-commerce', 'Boutique en ligne développée avec PHP et MySQL', 'ecommerce.jpg', 'https://github.com/john/ecommerce'),
(2, 'Blog Personnel', 'Blog développé avec Laravel et Bootstrap', 'blog.jpg', 'https://github.com/john/blog'),
(2, 'Application Mobile', 'Application mobile hybride avec React Native', 'mobile.jpg', 'https://github.com/john/mobile'),

-- Projets de Jane Smith
(3, 'Dashboard Analytics', 'Tableau de bord d\'analytics avec JavaScript et Chart.js', 'dashboard.jpg', 'https://github.com/jane/dashboard'),
(3, 'Système de Réservation', 'Application de réservation en ligne avec PHP', 'reservation.jpg', 'https://github.com/jane/reservation'),
(3, 'Portfolio Créatif', 'Portfolio artistique avec animations CSS avancées', 'creative.jpg', 'https://github.com/jane/creative');

-- Compétences des utilisateurs
INSERT INTO user_skills (user_id, skill_id, level) VALUES
-- Admin (toutes les compétences)
(1, 1, 'expert'), (1, 2, 'avancé'), (1, 3, 'expert'), (1, 4, 'avancé'), (1, 5, 'intermédiaire'),
(1, 6, 'expert'), (1, 7, 'avancé'), (1, 8, 'intermédiaire'), (1, 9, 'débutant'), (1, 10, 'intermédiaire'),
(1, 11, 'avancé'), (1, 12, 'intermédiaire'),

-- John Doe (compétences backend)
(2, 1, 'avancé'), (2, 4, 'avancé'), (2, 7, 'avancé'), (2, 6, 'intermédiaire'), (2, 10, 'débutant'),

-- Jane Smith (compétences frontend)
(3, 2, 'expert'), (3, 3, 'expert'), (3, 5, 'avancé'), (3, 8, 'avancé'), (3, 6, 'intermédiaire');

-- Index pour optimiser les performances
CREATE INDEX idx_projects_user ON projects(user_id);
CREATE INDEX idx_user_skills_user ON user_skills(user_id);
CREATE INDEX idx_user_skills_skill ON user_skills(skill_id);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username); 