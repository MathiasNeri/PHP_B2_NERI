<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Contrôleur de gestion des utilisateurs (admin)
 */
class UserController {
    private $userModel;
    private $authController;
    
    public function __construct() {
        $this->userModel = new User();
        $this->authController = new AuthController();
    }
    
    /**
     * Afficher la liste des utilisateurs (admin)
     */
    public function index() {
        $this->authController->requireAdmin();
        
        $users = $this->userModel->getAll();
        $user = $this->authController->getCurrentUser();
        $authController = $this->authController;
        
        include __DIR__ . '/../views/users/index.php';
    }
    
    /**
     * Afficher le formulaire de création d'utilisateur (admin)
     */
    public function createForm() {
        $this->authController->requireAdmin();
        
        $error = $_SESSION['error'] ?? '';
        $formData = $_SESSION['form_data'] ?? [];
        $user = $this->authController->getCurrentUser();
        $authController = $this->authController;
        unset($_SESSION['error'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/users/create.php';
    }
    
    /**
     * Traiter la création d'un utilisateur (admin)
     */
    public function create() {
        $this->authController->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=users');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=users&subaction=create');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? '')); // Normaliser l'email
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = trim($_POST['role'] ?? 'user');
        
        $errors = [];
        
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Le nom d\'utilisateur doit contenir au moins 3 caractères.';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        if (!in_array($role, ['user', 'admin'])) {
            $errors[] = 'Rôle invalide.';
        }
        
        // Vérifier si l'email existe déjà
        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Cet email est déjà utilisé.';
        }
        
        // Vérifier si le nom d'utilisateur existe déjà
        if ($this->userModel->usernameExists($username)) {
            $errors[] = 'Ce nom d\'utilisateur est déjà utilisé.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
                'role' => $role
            ];
            header('Location: index.php?action=users&subaction=create');
            exit;
        }
        
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];
        
        if ($this->userModel->create($userData)) {
            $_SESSION['success'] = 'Utilisateur créé avec succès !';
            header('Location: index.php?action=users');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la création de l\'utilisateur.';
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
                'role' => $role
            ];
            header('Location: index.php?action=users&subaction=create');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire d'édition d'utilisateur (admin)
     */
    public function editForm($id) {
        $this->authController->requireAdmin();
        
        $userToEdit = $this->userModel->getById($id);
        if (!$userToEdit) {
            $_SESSION['error'] = 'Utilisateur non trouvé.';
            header('Location: index.php?action=users');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        $formData = $_SESSION['form_data'] ?? $userToEdit;
        $currentUser = $this->authController->getCurrentUser();
        $authController = $this->authController;
        unset($_SESSION['error'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/users/edit.php';
    }
    
    /**
     * Traiter la modification d'un utilisateur (admin)
     */
    public function edit($id) {
        $this->authController->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=users');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=users&subaction=edit&id=' . $id);
            exit;
        }
        
        $userToEdit = $this->userModel->getById($id);
        if (!$userToEdit) {
            $_SESSION['error'] = 'Utilisateur non trouvé.';
            header('Location: index.php?action=users');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? 'user');
        
        $errors = [];
        
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Le nom d\'utilisateur doit contenir au moins 3 caractères.';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        if (!in_array($role, ['user', 'admin'])) {
            $errors[] = 'Rôle invalide.';
        }
        
        // Vérifier si l'email existe déjà (sauf pour l'utilisateur actuel)
        if ($this->userModel->emailExists($email, $id)) {
            $errors[] = 'Cet email est déjà utilisé.';
        }
        
        // Vérifier si le nom d'utilisateur existe déjà (sauf pour l'utilisateur actuel)
        if ($this->userModel->usernameExists($username, $id)) {
            $errors[] = 'Ce nom d\'utilisateur est déjà utilisé.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
                'role' => $role
            ];
            header('Location: index.php?action=users&subaction=edit&id=' . $id);
            exit;
        }
        
        $userData = [
            'username' => $username,
            'email' => $email
        ];
        
        if ($this->userModel->update($id, $userData)) {
            $_SESSION['success'] = 'Utilisateur modifié avec succès !';
            header('Location: index.php?action=users');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la modification de l\'utilisateur.';
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
                'role' => $role
            ];
            header('Location: index.php?action=users&subaction=edit&id=' . $id);
            exit;
        }
    }
    
    /**
     * Supprimer un utilisateur (admin)
     */
    public function delete($id) {
        $this->authController->requireAdmin();
        
        $userToDelete = $this->userModel->getById($id);
        if (!$userToDelete) {
            $_SESSION['error'] = 'Utilisateur non trouvé.';
            header('Location: index.php?action=users');
            exit;
        }
        
        $currentUser = $this->authController->getCurrentUser();
        if ($userToDelete['id'] == $currentUser['id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte.';
            header('Location: index.php?action=users');
            exit;
        }
        
        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Utilisateur supprimé avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de l\'utilisateur.';
        }
        
        header('Location: index.php?action=users');
        exit;
    }
} 