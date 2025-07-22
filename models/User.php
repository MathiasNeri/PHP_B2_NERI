<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Classe User - Gestion des utilisateurs
 */
class User {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function create($data) {
        $sql = "INSERT INTO users (username, email, password, role, created_at) 
                VALUES (:username, :email, :password, :role, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => PASSWORD_COST]);
        
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $data['role'] ?? 'user'
        ]);
    }
    
    /**
     * Authentifier un utilisateur
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Ne pas stocker le mot de passe en session
            return $user;
        }
        
        return false;
    }
    
    /**
     * Récupérer un utilisateur par ID
     */
    public function getById($id) {
        $sql = "SELECT id, username, email, role, created_at, bio, profile_picture, security_question, security_answer, profile_completed FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer un utilisateur par email
     */
    public function getByEmail($email) {
        $sql = "SELECT id, username, email, role, created_at FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer un utilisateur par ID avec mot de passe (pour vérification)
     */
    public function getByIdWithPassword($id) {
        $sql = "SELECT id, username, email, password, role, created_at FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Mettre à jour un utilisateur
     */
    public function update($id, $data) {
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'id' => $id
        ]);
    }
    
    /**
     * Changer le mot de passe
     */
    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => PASSWORD_COST]);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $id
        ]);
    }
    
    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = ['email' => $email];
        if ($excludeId) {
            $params['exclude_id'] = $excludeId;
        }
        
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Vérifier si un nom d'utilisateur existe déjà
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = ['username' => $username];
        if ($excludeId) {
            $params['exclude_id'] = $excludeId;
        }
        
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Récupérer tous les utilisateurs (admin)
     */
    public function getAll() {
        $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Récupérer un utilisateur par email avec question de sécurité
     */
    public function getByEmailWithSecurity($email) {
        $sql = "SELECT id, username, email, bio, profile_picture, security_question, security_answer FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Vérifier la réponse à la question de sécurité
     */
    public function verifySecurityAnswer($email, $answer) {
        $user = $this->getByEmailWithSecurity($email);
        if (!$user) {
            return false;
        }
        
        // Vérifier le hash de la réponse
        return password_verify(strtolower(trim($answer)), $user['security_answer']);
    }
    
    /**
     * Mettre à jour la question de sécurité
     */
    public function updateSecurityQuestion($userId, $question, $answer) {
        // Hasher la réponse de sécurité
        $hashedAnswer = password_hash(strtolower(trim($answer)), PASSWORD_DEFAULT, ['cost' => PASSWORD_COST]);
        
        $sql = "UPDATE users SET security_question = :question, security_answer = :answer WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'question' => $question,
            'answer' => $hashedAnswer,
            'id' => $userId
        ]);
    }
    
    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => PASSWORD_COST]);
        
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $userId
        ]);
    }
    
    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile($userId, $data) {
        // Construire la requête SQL dynamiquement
        $fields = [];
        $params = ['id' => $userId];
        
        if (isset($data['username'])) {
            $fields[] = 'username = :username';
            $params['username'] = $data['username'];
        }
        
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }
        
        if (isset($data['bio'])) {
            $fields[] = 'bio = :bio';
            $params['bio'] = $data['bio'];
        }
        
        if (isset($data['profile_picture'])) {
            $fields[] = 'profile_picture = :profile_picture';
            $params['profile_picture'] = $data['profile_picture'];
        }
        
        if (empty($fields)) {
            return false; // Aucun champ à mettre à jour
        }
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Marquer le profil comme complété
     */
    public function markProfileCompleted($userId) {
        $sql = "UPDATE users SET profile_completed = TRUE WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }
    
    /**
     * Calculer le pourcentage de complétion du profil
     */
    public function getProfileCompletion($userId) {
        $user = $this->getById($userId);
        if (!$user) {
            return 0;
        }
        
        // Champs obligatoires (doivent être remplis)
        $requiredFields = [
            'username' => !empty($user['username']),
            'email' => !empty($user['email']),
            'bio' => !empty($user['bio']),
            'security_question' => !empty($user['security_question']),
            'security_answer' => !empty($user['security_answer'])
        ];
        
        $completedRequired = array_sum($requiredFields);
        $totalRequired = count($requiredFields);
        
        // Calcul : 100% si tous les champs obligatoires sont remplis
        $basePercentage = ($completedRequired / $totalRequired) * 100;
        
        // Bonus de 10% si photo de profil
        $bonus = !empty($user['profile_picture']) ? 10 : 0;
        
        return min(100, round($basePercentage + $bonus));
    }
    
    /**
     * Mettre à jour la photo de profil
     */
    public function updateProfilePicture($userId, $fileName) {
        $sql = "UPDATE users SET profile_picture = :profile_picture WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'profile_picture' => $fileName,
            'id' => $userId
        ]);
    }
} 