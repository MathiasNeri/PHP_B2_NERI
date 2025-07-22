-- Script pour corriger l'encodage des utilisateurs
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Mettre à jour les utilisateurs avec le bon encodage UTF-8
UPDATE users SET 
    bio = 'Administrateur principal du système. Gestion complète des utilisateurs et des compétences.'
WHERE id = 1;

UPDATE users SET 
    bio = 'Développeur web passionné par les nouvelles technologies.'
WHERE id = 2;

UPDATE users SET 
    bio = 'Designer créatif spécialisé dans l''expérience utilisateur.'
WHERE id = 3; 