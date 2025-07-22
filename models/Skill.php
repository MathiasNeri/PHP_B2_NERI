<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/EncodingHelper.php';

/**
 * Classe Skill - Gestion des compétences
 */
class Skill {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }
    
    /**
     * Corriger automatiquement l'encodage des données de compétence
     */
    private function fixSkillData($data) {
        if (is_array($data)) {
            $data['name'] = EncodingHelper::fixEncoding($data['name'] ?? '');
            $data['description'] = EncodingHelper::fixEncoding($data['description'] ?? '');
            $data['category'] = EncodingHelper::fixEncoding($data['category'] ?? '');
        }
        return $data;
    }
    
    /**
     * Créer une nouvelle compétence
     */
    public function create($data) {
        $data = $this->fixSkillData($data);
        
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
        $skill = $stmt->fetch();
        
        return $skill ? EncodingHelper::fixSkillData($skill) : $skill;
    }
    
    /**
     * Récupérer toutes les compétences (admin)
     */
    public function getAll() {
        $sql = "SELECT * FROM skills ORDER BY is_public DESC, category, name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $skills = $stmt->fetchAll();
        
        // Corriger l'encodage de toutes les compétences
        return array_map([$this, 'fixSkillData'], $skills);
    }
    
    /**
     * Récupérer les compétences publiques (pour les utilisateurs)
     */
    public function getPublic() {
        $sql = "SELECT * FROM skills WHERE is_public = TRUE ORDER BY category, name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $skills = $stmt->fetchAll();
        
        // Corriger l'encodage de toutes les compétences
        return array_map([$this, 'fixSkillData'], $skills);
    }
    
    /**
     * Récupérer les compétences privées d'un utilisateur
     */
    public function getPrivateByUserId($userId) {
        $sql = "SELECT s.* FROM skills s 
                JOIN user_skills us ON s.id = us.skill_id 
                WHERE us.user_id = :user_id AND s.is_public = FALSE 
                ORDER BY s.category, s.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $skills = $stmt->fetchAll();
        
        // Corriger l'encodage de toutes les compétences
        return array_map([$this, 'fixSkillData'], $skills);
    }
    
    /**
     * Récupérer les compétences par catégorie
     */
    public function getByCategory($category) {
        $sql = "SELECT * FROM skills WHERE category = :category ORDER BY name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category' => $category]);
        $skills = $stmt->fetchAll();
        
        // Corriger l'encodage de toutes les compétences
        return array_map([$this, 'fixSkillData'], $skills);
    }
    
    /**
     * Mettre à jour une compétence
     */
    public function update($id, $data) {
        $data = $this->fixSkillData($data);
        
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
    public function exists($name, $excludeId = null) {
        $name = EncodingHelper::fixEncoding($name);
        
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
        $skills = $stmt->fetchAll();
        
        // Corriger l'encodage de toutes les compétences
        return array_map([$this, 'fixSkillData'], $skills);
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
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Corriger l'encodage de toutes les catégories
        return array_map([EncodingHelper::class, 'fixEncoding'], $categories);
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