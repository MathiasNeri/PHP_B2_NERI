-- Script de mise à jour de la base de données
-- Ajouter la colonne is_public à la table skills
ALTER TABLE skills ADD COLUMN is_public BOOLEAN DEFAULT TRUE;

-- Supprimer les photos de profil des utilisateurs
UPDATE users SET profile_picture = NULL WHERE profile_picture IS NOT NULL;

-- Supprimer les images des projets
UPDATE projects SET image = NULL WHERE image IS NOT NULL;

-- Mettre à jour toutes les compétences existantes pour qu'elles soient publiques
UPDATE skills SET is_public = TRUE WHERE is_public IS NULL; 