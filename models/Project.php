<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/EncodingHelper.php';

/**
 * Classe Project - Gestion des projets
 */
class Project {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }
    
    /**
     * Corriger automatiquement l'encodage des données de projet
     */
    private function fixProjectData($data) {
        if (is_array($data)) {
            $data['title'] = EncodingHelper::fixEncoding($data['title'] ?? '');
            $data['description'] = EncodingHelper::fixEncoding($data['description'] ?? '');
            $data['link'] = EncodingHelper::fixEncoding($data['link'] ?? '');
        }
        return $data;
    }
    
    /**
     * Créer un nouveau projet
     */
    public function create($data) {
        $data = $this->fixProjectData($data);
        
        $sql = "INSERT INTO projects (user_id, title, description, image, link, created_at) 
                VALUES (:user_id, :title, :description, :image, :link, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image'] ?? null,
            'link' => $data['link'] ?? null
        ]);
    }
    
    /**
     * Récupérer un projet par ID
     */
    public function getById($id) {
        $sql = "SELECT p.*, u.username FROM projects p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch();
        
        return $project ? EncodingHelper::fixProjectData($project) : $project;
    }
    
    /**
     * Récupérer tous les projets d'un utilisateur
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM projects WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $projects = $stmt->fetchAll();
        
        // Corriger l'encodage de tous les projets
        return array_map([$this, 'fixProjectData'], $projects);
    }
    
    /**
     * Récupérer tous les projets (admin)
     */
    public function getAll() {
        $sql = "SELECT p.*, u.username FROM projects p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $projects = $stmt->fetchAll();
        
        // Corriger l'encodage de tous les projets
        return array_map([$this, 'fixProjectData'], $projects);
    }
    
    /**
     * Récupérer tous les projets avec informations utilisateurs détaillées (admin)
     */
    public function getAllWithUsers() {
        $sql = "SELECT p.*, u.username, u.email, u.role 
                FROM projects p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $projects = $stmt->fetchAll();
        
        // Corriger l'encodage de tous les projets
        return array_map([$this, 'fixProjectData'], $projects);
    }
    
    /**
     * Mettre à jour un projet
     */
    public function update($id, $data) {
        $data = $this->fixProjectData($data);
        
        $sql = "UPDATE projects SET title = :title, description = :description, 
                image = :image, link = :link, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image'] ?? null,
            'link' => $data['link'] ?? null,
            'id' => $id
        ]);
    }
    
    /**
     * Supprimer un projet
     */
    public function delete($id) {
        // Récupérer l'image avant suppression pour la supprimer du serveur
        $project = $this->getById($id);
        if ($project && $project['image']) {
            $imagePath = UPLOAD_PATH . $project['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Vérifier si un projet appartient à un utilisateur
     */
    public function belongsToUser($projectId, $userId) {
        $sql = "SELECT COUNT(*) FROM projects WHERE id = :project_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'project_id' => $projectId,
            'user_id' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Compter les projets d'un utilisateur
     */
    public function countByUserId($userId) {
        $sql = "SELECT COUNT(*) FROM projects WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
} 