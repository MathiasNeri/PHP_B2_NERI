-- Script pour corriger l'encodage des compétences
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Mettre à jour les compétences avec le bon encodage UTF-8
UPDATE skills SET 
    name = 'PHP',
    description = 'Langage de programmation côté serveur'
WHERE id = 1;

UPDATE skills SET 
    name = 'JavaScript',
    description = 'Langage de programmation côté client'
WHERE id = 2;

UPDATE skills SET 
    name = 'HTML/CSS',
    description = 'Langages de balisage et de style'
WHERE id = 3;

UPDATE skills SET 
    name = 'Python',
    description = 'Langage de programmation polyvalent'
WHERE id = 4;

UPDATE skills SET 
    name = 'Java',
    description = 'Langage de programmation orienté objet'
WHERE id = 5;

UPDATE skills SET 
    name = 'C++',
    description = 'Langage de programmation système'
WHERE id = 6;

UPDATE skills SET 
    name = 'MySQL',
    description = 'Système de gestion de base de données'
WHERE id = 7;

UPDATE skills SET 
    name = 'PostgreSQL',
    description = 'Système de gestion de base de données avancé'
WHERE id = 8;

UPDATE skills SET 
    name = 'MongoDB',
    description = 'Base de données NoSQL'
WHERE id = 9;

UPDATE skills SET 
    name = 'Git',
    description = 'Système de contrôle de version'
WHERE id = 10;

UPDATE skills SET 
    name = 'Docker',
    description = 'Plateforme de conteneurisation'
WHERE id = 11;

UPDATE skills SET 
    name = 'Linux',
    description = 'Système d''exploitation open source'
WHERE id = 12;

UPDATE skills SET 
    name = 'Bootstrap',
    description = 'Framework CSS pour le design responsive'
WHERE id = 13;

UPDATE skills SET 
    name = 'React',
    description = 'Bibliothèque JavaScript pour les interfaces'
WHERE id = 14;

UPDATE skills SET 
    name = 'Vue.js',
    description = 'Framework JavaScript progressif'
WHERE id = 15;

UPDATE skills SET 
    name = 'Laravel',
    description = 'Framework PHP pour le développement web'
WHERE id = 16;

UPDATE skills SET 
    name = 'Symfony',
    description = 'Framework PHP pour applications web'
WHERE id = 17;

UPDATE skills SET 
    name = 'Node.js',
    description = 'Environnement JavaScript côté serveur'
WHERE id = 18;

UPDATE skills SET 
    name = 'Photoshop',
    description = 'Logiciel de retouche d''image'
WHERE id = 19;

UPDATE skills SET 
    name = 'Figma',
    description = 'Outil de design collaboratif'
WHERE id = 20;

UPDATE skills SET 
    name = 'Adobe XD',
    description = 'Outil de design d''interface'
WHERE id = 21;

UPDATE skills SET 
    name = 'WordPress',
    description = 'Système de gestion de contenu'
WHERE id = 22;

UPDATE skills SET 
    name = 'VS Code',
    description = 'Éditeur de code source'
WHERE id = 23;

UPDATE skills SET 
    name = 'PhpStorm',
    description = 'IDE pour le développement PHP'
WHERE id = 24;

UPDATE skills SET 
    name = 'GitHub',
    description = 'Plateforme d''hébergement de code'
WHERE id = 25;

UPDATE skills SET 
    name = 'AWS',
    description = 'Services cloud d''Amazon'
WHERE id = 26;

UPDATE skills SET 
    name = 'Azure',
    description = 'Services cloud de Microsoft'
WHERE id = 27;

UPDATE skills SET 
    name = 'Google Cloud',
    description = 'Services cloud de Google'
WHERE id = 28; 