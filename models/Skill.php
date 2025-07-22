<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Classe Skill - Gestion des compétences
 */
class Skill {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }
    
    /**
     * Créer une nouvelle compétence
     */
    public function create($data) {
        $sql = "INSERT INTO skills (name, description, category, is_public, created_at) 
                VALUES (:name, :description, :category, :is_public, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'] ?? 'general',
            'is_public' => $data['is_public'] ?? false
        ]);
        
        if ($result) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Récupérer une compétence par ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM skills WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer toutes les compétences (admin)
     */
    public function getAll() {
        $sql = "SELECT * FROM skills ORDER BY is_public DESC, category, name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les compétences publiques (pour les utilisateurs)
     */
    public function getPublic() {
        $sql = "SELECT * FROM skills WHERE is_public = TRUE ORDER BY category, name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les compétences privées d'un utilisateur
     */
    public function getPrivateByUser($userId) {
        $sql = "SELECT s.* FROM skills s 
                JOIN user_skills us ON s.id = us.skill_id 
                WHERE us.user_id = :user_id AND s.is_public = FALSE 
                ORDER BY s.category, s.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les compétences par catégorie
     */
    public function getByCategory($category) {
        $sql = "SELECT * FROM skills WHERE category = :category ORDER BY name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mettre à jour une compétence
     */
    public function update($id, $data) {
        $sql = "UPDATE skills SET name = :name, description = :description, 
                category = :category WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'id' => $id
        ]);
    }
    
    /**
     * Supprimer une compétence
     */
    public function delete($id) {
        // Supprimer d'abord les liaisons utilisateurs-compétences
        $sql = "DELETE FROM user_skills WHERE skill_id = :skill_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['skill_id' => $id]);
        
        // Puis supprimer la compétence
        $sql = "DELETE FROM skills WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Vérifier si une compétence existe déjà
     */
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM skills WHERE name = :name";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = ['name' => $name];
        if ($excludeId) {
            $params['exclude_id'] = $excludeId;
        }
        
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Récupérer les compétences d'un utilisateur avec niveaux
     */
    public function getUserSkills($userId) {
        $sql = "SELECT s.*, us.level, us.skill_id FROM skills s 
                JOIN user_skills us ON s.id = us.skill_id 
                WHERE us.user_id = :user_id 
                ORDER BY us.created_at DESC, s.category, s.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Ajouter une compétence à un utilisateur
     */
    public function addToUser($userId, $skillId, $level = 'débutant') {
        $sql = "INSERT INTO user_skills (user_id, skill_id, level, created_at) 
                VALUES (:user_id, :skill_id, :level, NOW()) 
                ON DUPLICATE KEY UPDATE level = :level_update";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'skill_id' => $skillId,
            'level' => $level,
            'level_update' => $level
        ]);
    }
    
    /**
     * Supprimer une compétence d'un utilisateur
     */
    public function removeFromUser($userId, $skillId) {
        $sql = "DELETE FROM user_skills WHERE user_id = :user_id AND skill_id = :skill_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'skill_id' => $skillId
        ]);
    }
    
    /**
     * Mettre à jour le niveau d'une compétence utilisateur
     */
    public function updateUserSkillLevel($userId, $skillId, $level) {
        $sql = "UPDATE user_skills SET level = :level WHERE user_id = :user_id AND skill_id = :skill_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'skill_id' => $skillId,
            'level' => $level
        ]);
    }
    
    /**
     * Récupérer les catégories disponibles
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM skills ORDER BY category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Promouvoir une compétence privée en publique
     */
    public function makePublic($id) {
        $sql = "UPDATE skills SET is_public = TRUE WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Rendre une compétence privée
     */
    public function makePrivate($id) {
        $sql = "UPDATE skills SET is_public = FALSE WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
} 