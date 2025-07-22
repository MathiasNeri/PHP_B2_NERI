<?php
require_once __DIR__ . '/../models/Skill.php';

/**
 * Contrôleur de gestion des compétences
 */
class SkillController {
    private $skillModel;
    private $authController;
    private $pdo;
    
    public function __construct() {
        $this->skillModel = new Skill();
        $this->authController = new AuthController();
        $this->pdo = getDatabaseConnection();
    }
    
    /**
     * Afficher la liste des compétences (admin)
     */
    public function index() {
        $this->authController->requireAdmin();
        
        $user = $this->authController->getCurrentUser();
        $skills = $this->skillModel->getAll();
        $categories = $this->skillModel->getCategories();
        $authController = $this->authController;
        
        include __DIR__ . '/../views/skills/index.php';
    }
    
    /**
     * Afficher les compétences de l'utilisateur
     */
    public function userSkills() {
        $this->authController->requireAuth();
        
        $user = $this->authController->getCurrentUser();
        $userSkills = $this->skillModel->getUserSkills($user['id']);
        $availableSkills = $this->skillModel->getPublic();
        $privateSkills = $this->skillModel->getPrivateByUser($user['id']);
        $authController = $this->authController;
        
        include __DIR__ . '/../views/skills/user_skills.php';
    }
    
    /**
     * Afficher le formulaire de création de compétence (admin)
     */
    public function createForm() {
        $this->authController->requireAdmin();
        
        $user = $this->authController->getCurrentUser();
        $error = $_SESSION['error'] ?? '';
        $formData = $_SESSION['form_data'] ?? [];
        $authController = $this->authController;
        unset($_SESSION['error'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/skills/create.php';
    }
    
    /**
     * Traiter la création d'une compétence (admin)
     */
    public function create() {
        $this->authController->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=skills');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=skills&subaction=create');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        
        $errors = [];
        
        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Le nom doit contenir au moins 2 caractères.';
        }
        
        if (empty($description) || strlen($description) < 5) {
            $errors[] = 'La description doit contenir au moins 5 caractères.';
        }
        
        if (empty($category)) {
            $errors[] = 'La catégorie est requise.';
        }
        
        // Vérifier si le nom existe déjà
        if ($this->skillModel->nameExists($name)) {
            $errors[] = 'Une compétence avec ce nom existe déjà.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'name' => $name,
                'description' => $description,
                'category' => $category
            ];
            header('Location: index.php?action=skills&subaction=create');
            exit;
        }
        
        $skillData = [
            'name' => $name,
            'description' => $description,
            'category' => $category
        ];
        
        if ($this->skillModel->create($skillData)) {
            $_SESSION['success'] = 'Compétence créée avec succès !';
            header('Location: index.php?action=skills');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la création de la compétence.';
            $_SESSION['form_data'] = [
                'name' => $name,
                'description' => $description,
                'category' => $category
            ];
            header('Location: index.php?action=skills&subaction=create');
            exit;
        }
    }
    
    /**
     * Ajouter une compétence à un utilisateur
     */
    public function addToUser() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=skills&subaction=user');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        $skillId = trim($_POST['skill_id'] ?? '');
        $level = trim($_POST['level'] ?? '');
        
        // Gestion des compétences personnalisées
        if ($skillId === 'custom') {
            $customName = trim($_POST['custom_skill_name'] ?? '');
            $customDescription = trim($_POST['custom_skill_description'] ?? '');
            $customCategory = trim($_POST['custom_skill_category'] ?? '');
            
            // Validation des données
            if (empty($customName) || empty($customDescription) || empty($customCategory)) {
                $_SESSION['error'] = 'Tous les champs sont requis pour une compétence personnalisée.';
                header('Location: index.php?action=skills&subaction=user');
                exit;
            }
            
            // Validation du nom (lettres, espaces, tirets, points uniquement)
            if (!preg_match('/^[A-Za-zÀ-ÿ\s\-\.]+$/', $customName)) {
                $_SESSION['error'] = 'Le nom de la compétence ne peut contenir que des lettres, espaces, tirets et points.';
                header('Location: index.php?action=skills&subaction=user');
                exit;
            }
            
            // Vérifier si la compétence existe déjà
            if ($this->skillModel->nameExists($customName)) {
                $_SESSION['error'] = 'Une compétence avec ce nom existe déjà.';
                header('Location: index.php?action=skills&subaction=user');
                exit;
            }
            
            // Créer la nouvelle compétence (privée par défaut)
            $skillData = [
                'name' => $customName,
                'description' => $customDescription,
                'category' => $customCategory,
                'is_public' => false
            ];
            
            $skillId = $this->skillModel->create($skillData);
            if ($skillId) {
                if ($this->skillModel->addToUser($user['id'], $skillId, $level)) {
                    $_SESSION['success'] = 'Compétence personnalisée créée et ajoutée avec succès !';
                } else {
                    $_SESSION['error'] = 'Erreur lors de l\'ajout de la compétence.';
                }
            } else {
                $_SESSION['error'] = 'Erreur lors de la création de la compétence personnalisée.';
            }
        } else {
            // Compétence existante
            $skillId = filter_input(INPUT_POST, 'skill_id', FILTER_VALIDATE_INT);
            
            if (!$skillId) {
                $_SESSION['error'] = 'Compétence invalide.';
                header('Location: index.php?action=skills&subaction=user');
                exit;
            }
            
            if ($this->skillModel->addToUser($user['id'], $skillId, $level)) {
                $_SESSION['success'] = 'Compétence ajoutée avec succès !';
            } else {
                $_SESSION['error'] = 'Erreur lors de l\'ajout de la compétence.';
            }
        }
        
        header('Location: index.php?action=skills&subaction=user');
        exit;
    }
    
    /**
     * Supprimer une compétence d'un utilisateur
     */
    public function removeFromUser() {
        $this->authController->requireAuth();
        
        $user = $this->authController->getCurrentUser();
        $skillId = filter_input(INPUT_GET, 'skill_id', FILTER_VALIDATE_INT);
        
        if (!$skillId) {
            $_SESSION['error'] = 'Compétence invalide.';
            header('Location: index.php?action=skills&subaction=user');
            exit;
        }
        
        if ($this->skillModel->removeFromUser($user['id'], $skillId)) {
            $_SESSION['success'] = 'Compétence supprimée avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de la compétence.';
        }
        
        header('Location: index.php?action=skills&subaction=user');
        exit;
    }
    
    /**
     * Supprimer une compétence (admin)
     */
    public function delete() {
        $this->authController->requireAdmin();
        
        $skillId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$skillId) {
            $_SESSION['error'] = 'Compétence invalide.';
            header('Location: index.php?action=skills');
            exit;
        }
        
        if ($this->skillModel->delete($skillId)) {
            $_SESSION['success'] = 'Compétence supprimée avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de la compétence.';
        }
        
        header('Location: index.php?action=skills');
        exit;
    }
    
    /**
     * Promouvoir une compétence privée en publique (admin)
     */
    public function makePublic() {
        $this->authController->requireAdmin();
        
        $skillId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$skillId) {
            $_SESSION['error'] = 'Compétence invalide.';
            header('Location: index.php?action=skills');
            exit;
        }
        
        if ($this->skillModel->makePublic($skillId)) {
            $_SESSION['success'] = 'Compétence promue en publique avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la promotion de la compétence.';
        }
        
        header('Location: index.php?action=skills');
        exit;
    }
    
    /**
     * Rendre une compétence privée (admin)
     */
    public function makePrivate() {
        $this->authController->requireAdmin();
        
        $skillId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$skillId) {
            $_SESSION['error'] = 'Compétence invalide.';
            header('Location: index.php?action=skills');
            exit;
        }
        
        if ($this->skillModel->makePrivate($skillId)) {
            $_SESSION['success'] = 'Compétence rendue privée avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la modification de la compétence.';
        }
        
        header('Location: index.php?action=skills');
        exit;
    }
} 