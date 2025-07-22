<?php
require_once __DIR__ . '/../models/Project.php';

/**
 * Contrôleur de gestion des projets
 */
class ProjectController {
    private $projectModel;
    private $authController;
    
    public function __construct() {
        $this->projectModel = new Project();
        $this->authController = new AuthController();
    }
    
    /**
     * Afficher la liste des projets
     */
    public function index() {
        $this->authController->requireAuth();
        
        $user = $this->authController->getCurrentUser();
        $authController = $this->authController; // Passer la variable à la vue
        
        // Pour les admins, afficher seulement leurs projets
        $projects = $this->projectModel->getByUserId($user['id']);
        
        include __DIR__ . '/../views/projects/index.php';
    }
    
    /**
     * Afficher la liste de tous les projets (admin seulement)
     */
    public function adminIndex() {
        $this->authController->requireAdmin();
        
        $user = $this->authController->getCurrentUser();
        $authController = $this->authController;
        
        // Récupérer tous les projets avec les informations des utilisateurs
        $projects = $this->projectModel->getAllWithUsers();
        
        include __DIR__ . '/../views/projects/admin_index.php';
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function createForm() {
        $this->authController->requireAuth();
        
        $error = $_SESSION['error'] ?? '';
        $formData = $_SESSION['form_data'] ?? [];
        $user = $this->authController->getCurrentUser();
        $authController = $this->authController;
        unset($_SESSION['error'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/projects/create.php';
    }
    
    /**
     * Traiter la création d'un projet
     */
    public function create() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=projects');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=projects&subaction=create');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        
        // Validation
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        
        $errors = [];
        
        if (empty($title) || strlen($title) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères.';
        }
        
        if (empty($description) || strlen($description) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères.';
        }
        
        if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
            $errors[] = 'Le lien doit être une URL valide.';
        }
        
        // Gestion de l'upload d'image
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if (is_string($uploadResult)) {
                $imageName = $uploadResult;
            } else {
                $errors[] = $uploadResult;
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'title' => $title,
                'description' => $description,
                'link' => $link
            ];
            header('Location: index.php?action=projects&subaction=create');
            exit;
        }
        
        // Créer le projet
        $projectData = [
            'user_id' => $user['id'],
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'image' => $imageName
        ];
        
        if ($this->projectModel->create($projectData)) {
            $_SESSION['success'] = 'Projet créé avec succès !';
            header('Location: index.php?action=projects');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la création du projet.';
            $_SESSION['form_data'] = [
                'title' => $title,
                'description' => $description,
                'link' => $link
            ];
            header('Location: index.php?action=projects&subaction=create');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function editForm($id) {
        $this->authController->requireAuth();
        
        $project = $this->projectModel->getById($id);
        if (!$project) {
            $_SESSION['error'] = 'Projet non trouvé.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        if (!$this->authController->isAdmin() && !$this->projectModel->belongsToUser($id, $user['id'])) {
            $_SESSION['error'] = 'Vous n\'avez pas les permissions pour modifier ce projet.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        $authController = $this->authController;
        unset($_SESSION['error']);
        
        include __DIR__ . '/../views/projects/edit.php';
    }
    
    /**
     * Traiter la modification d'un projet
     */
    public function edit($id) {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=projects');
            exit;
        }
        
        $project = $this->projectModel->getById($id);
        if (!$project) {
            $_SESSION['error'] = 'Projet non trouvé.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        if (!$this->authController->isAdmin() && !$this->projectModel->belongsToUser($id, $user['id'])) {
            $_SESSION['error'] = 'Vous n\'avez pas les permissions pour modifier ce projet.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        // Validation
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        
        $errors = [];
        
        if (empty($title) || strlen($title) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères.';
        }
        
        if (empty($description) || strlen($description) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères.';
        }
        
        if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
            $errors[] = 'Le lien doit être une URL valide.';
        }
        
        // Gestion de l'upload d'image
        $imageName = $project['image']; // Garder l'image existante par défaut
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if (is_string($uploadResult)) {
                // Supprimer l'ancienne image
                if ($project['image']) {
                    $oldImagePath = UPLOAD_PATH . $project['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imageName = $uploadResult;
            } else {
                $errors[] = $uploadResult;
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: index.php?action=projects&subaction=edit&id=' . $id);
            exit;
        }
        
        // Mettre à jour le projet
        $projectData = [
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'image' => $imageName
        ];
        
        if ($this->projectModel->update($id, $projectData)) {
            $_SESSION['success'] = 'Projet modifié avec succès !';
            header('Location: index.php?action=projects');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la modification du projet.';
            header('Location: index.php?action=projects&subaction=edit&id=' . $id);
            exit;
        }
    }
    
    /**
     * Supprimer un projet
     */
    public function delete($id) {
        $this->authController->requireAuth();
        
        $project = $this->projectModel->getById($id);
        if (!$project) {
            $_SESSION['error'] = 'Projet non trouvé.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        if (!$this->authController->isAdmin() && !$this->projectModel->belongsToUser($id, $user['id'])) {
            $_SESSION['error'] = 'Vous n\'avez pas les permissions pour supprimer ce projet.';
            header('Location: index.php?action=projects');
            exit;
        }
        
        if ($this->projectModel->delete($id)) {
            $_SESSION['success'] = 'Projet supprimé avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression du projet.';
        }
        
        header('Location: index.php?action=projects');
        exit;
    }
    
    /**
     * Gérer l'upload d'image
     */
    private function handleImageUpload($file) {
        // Vérifier la taille
        if ($file['size'] > MAX_FILE_SIZE) {
            return 'L\'image est trop volumineuse (max 5MB).';
        }
        
        // Vérifier le type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, ALLOWED_EXTENSIONS)) {
            return 'Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.';
        }
        
        // Générer un nom unique
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = UPLOAD_PATH . $fileName;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $fileName;
        } else {
            return 'Erreur lors de l\'upload du fichier.';
        }
    }
} 