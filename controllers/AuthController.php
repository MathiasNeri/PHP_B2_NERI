<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Contrôleur d'authentification
 */
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->initSession();
    }
    
    /**
     * Initialiser la session avec sécurité
     */
    private function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Régénérer l'ID de session pour éviter la fixation
        if (!isset($_SESSION['initialized'])) {
            session_regenerate_id(true);
            $_SESSION['initialized'] = true;
        }
        
        // Vérifier l'expiration de session
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity']) > SESSION_LIFETIME) {
            $this->logout();
            return;
        }
        
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Afficher le formulaire de connexion
     */
    public function loginForm() {
        if ($this->isLoggedIn()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        $email = $_SESSION['form_data']['email'] ?? '';
        unset($_SESSION['error'], $_SESSION['success'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Traiter la connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=login');
            exit;
        }
        
        // Normaliser l'email en minuscules
        $email = strtolower(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)));
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Tous les champs sont requis.';
            $_SESSION['form_data'] = ['email' => $email];
            header('Location: index.php?action=login');
            exit;
        }
        
        // Authentification
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            // Créer la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;
            $_SESSION['last_activity'] = time();
            
            // Vérifier si le profil est complété
            $completion = $this->userModel->getProfileCompletion($user['id']);
            
            if ($completion < 100) {
                $_SESSION['profile_completion'] = $completion;
                header('Location: index.php?action=complete_profile');
                exit;
            }
            
            header('Location: index.php?action=dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Email ou mot de passe incorrect.';
            $_SESSION['form_data'] = ['email' => $email];
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire d'inscription
     */
    public function registerForm() {
        if ($this->isLoggedIn()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['error'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/auth/register.php';
    }
    
    /**
     * Traiter l'inscription
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=register');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? '')); // Normaliser l'email
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
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
                'email' => $email
            ];
            header('Location: index.php?action=register');
            exit;
        }
        
        // Créer l'utilisateur
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        ];
        
        if ($this->userModel->create($userData)) {
            $_SESSION['success'] = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
            header('Location: index.php?action=login');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la création du compte.';
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email
            ];
            header('Location: index.php?action=register');
            exit;
        }
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        // Détruire la session
        session_destroy();
        
        header('Location: index.php?action=login');
        exit;
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Récupérer l'utilisateur connecté
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        // Utiliser les données de session mises à jour si elles existent
        if (isset($_SESSION['user_data'])) {
            return $_SESSION['user_data'];
        }
        
        return $this->userModel->getById($_SESSION['user_id']);
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin() {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === 'admin';
    }
    
    /**
     * Générer un token CSRF
     */
    public function generateCSRFToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Vérifier le token CSRF
     */
    public function verifyCSRFToken() {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        return isset($_SESSION[CSRF_TOKEN_NAME]) && 
               hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
    
    /**
     * Middleware pour protéger les routes
     */
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    /**
     * Middleware pour protéger les routes admin
     */
    public function requireAdmin() {
        $this->requireAuth();
        
        if (!$this->isAdmin()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function forgotPasswordForm() {
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        
        include __DIR__ . '/../views/auth/forgot_password.php';
    }
    
    /**
     * Traiter la demande de réinitialisation
     */
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide.';
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        $user = $this->userModel->getByEmailWithSecurity($email);
        
        if ($user) {
            // Stocker l'email en session pour l'étape suivante
            $_SESSION['reset_email'] = $email;
            header('Location: index.php?action=security_question');
            exit;
        } else {
            $_SESSION['error'] = 'Si cet email existe dans notre base de données, vous serez redirigé vers la question de sécurité.';
            header('Location: index.php?action=forgot_password');
            exit;
        }
    }
    
    /**
     * Afficher la question de sécurité
     */
    public function securityQuestionForm() {
        $email = $_SESSION['reset_email'] ?? '';
        
        if (empty($email)) {
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        $user = $this->userModel->getByEmailWithSecurity($email);
        
        if (!$user) {
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        include __DIR__ . '/../views/auth/security_question.php';
    }
    
    /**
     * Traiter la réponse à la question de sécurité
     */
    public function securityQuestion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=security_question');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=security_question');
            exit;
        }
        
        $email = $_SESSION['reset_email'] ?? '';
        $answer = trim($_POST['security_answer'] ?? '');
        
        if (empty($email) || empty($answer)) {
            $_SESSION['error'] = 'Tous les champs sont requis.';
            header('Location: index.php?action=security_question');
            exit;
        }
        
        if ($this->userModel->verifySecurityAnswer($email, $answer)) {
            // Réponse correcte, permettre la réinitialisation
            $_SESSION['can_reset_password'] = true;
            header('Location: index.php?action=reset_password');
            exit;
        } else {
            $_SESSION['error'] = 'Réponse incorrecte. Veuillez réessayer.';
            header('Location: index.php?action=security_question');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire de réinitialisation
     */
    public function resetPasswordForm() {
        if (!isset($_SESSION['can_reset_password']) || !$_SESSION['can_reset_password']) {
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        include __DIR__ . '/../views/auth/reset_password.php';
    }
    
    /**
     * Traiter la réinitialisation du mot de passe
     */
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=reset_password');
            exit;
        }
        
        if (!isset($_SESSION['can_reset_password']) || !$_SESSION['can_reset_password']) {
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=reset_password');
            exit;
        }
        
        $email = $_SESSION['reset_email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: index.php?action=reset_password');
            exit;
        }
        
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            $_SESSION['error'] = 'Utilisateur non trouvé.';
            header('Location: index.php?action=forgot_password');
            exit;
        }
        
        // Réinitialiser le mot de passe
        if ($this->userModel->resetPassword($user['id'], $password)) {
            // Nettoyer les variables de session
            unset($_SESSION['reset_email'], $_SESSION['can_reset_password']);
            
            $_SESSION['success'] = 'Mot de passe réinitialisé avec succès ! Vous pouvez maintenant vous connecter.';
            header('Location: index.php?action=login');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la réinitialisation du mot de passe.';
            header('Location: index.php?action=reset_password');
            exit;
        }
    }
    
    /**
     * Afficher le formulaire de complétion du profil
     */
    public function completeProfileForm() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $userWithSecurity = $this->userModel->getByEmailWithSecurity($user['email']);
        $completion = $_SESSION['profile_completion'] ?? 0;
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        
        // Nettoyer les données de formulaire après affichage
        if (isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        
        include __DIR__ . '/../views/auth/complete_profile.php';
    }
    
    /**
     * Traiter la complétion du profil
     */
    public function completeProfile() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=complete_profile');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=complete_profile');
            exit;
        }
        
        $user = $this->getCurrentUser();
        $bio = trim($_POST['bio'] ?? '');
        $question = trim($_POST['security_question'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');
        
        $errors = [];
        
        if (empty($question)) {
            $errors[] = 'La question de sécurité est requise.';
        }
        
        if (empty($answer)) {
            $errors[] = 'La réponse de sécurité est requise.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'bio' => $bio,
                'security_question' => $question,
                'security_answer' => $answer
            ];
            header('Location: index.php?action=complete_profile');
            exit;
        }
        
        // Traiter l'upload de photo de profil
        $profilePicture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . '/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $file = $_FILES['profile_picture'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = MAX_FILE_SIZE;
            
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = 'Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.';
            }
            
            if ($file['size'] > $maxSize) {
                $errors[] = 'Fichier trop volumineux. Taille maximum : ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB.';
            }
            
            if (empty($errors)) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $user['id'] . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $profilePicture = 'profiles/' . $filename;
                } else {
                    $errors[] = 'Erreur lors de l\'upload du fichier.';
                }
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'bio' => $bio,
                'security_question' => $question,
                'security_answer' => $answer
            ];
            header('Location: index.php?action=complete_profile');
            exit;
        }
        
        // Mettre à jour le profil
        $profileData = [
            'username' => $user['username'],
            'email' => $user['email'],
            'bio' => $bio
        ];
        
        // Ajouter la photo de profil seulement si elle existe
        if ($profilePicture) {
            $profileData['profile_picture'] = $profilePicture;
        }
        
        if ($this->userModel->updateProfile($user['id'], $profileData) && 
            $this->userModel->updateSecurityQuestion($user['id'], $question, $answer)) {
            
            // Marquer le profil comme complété
            $this->userModel->markProfileCompleted($user['id']);
            
            unset($_SESSION['profile_completion']);
            $_SESSION['success'] = 'Profil complété avec succès !';
            header('Location: index.php?action=dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour du profil.';
            $_SESSION['form_data'] = [
                'bio' => $bio,
                'security_question' => $question,
                'security_answer' => $answer
            ];
            header('Location: index.php?action=complete_profile');
            exit;
        }
    }
} 