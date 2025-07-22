<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Contrôleur de gestion du profil utilisateur
 */
class ProfileController {
    private $userModel;
    private $authController;
    
    public function __construct() {
        $this->userModel = new User();
        $this->authController = new AuthController();
    }
    
    /**
     * Afficher le profil utilisateur
     */
    public function index() {
        $this->authController->requireAuth();
        
        $user = $this->authController->getCurrentUser();
        $userWithSecurity = $this->userModel->getByEmailWithSecurity($user['email']);
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        $authController = $this->authController;
        unset($_SESSION['error'], $_SESSION['success']);
        
        include __DIR__ . '/../views/profile/index.php';
    }
    
    /**
     * Traiter la modification du profil
     */
    public function update() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        $errors = [];
        
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Le nom d\'utilisateur doit contenir au moins 3 caractères.';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        // Vérifier si l'email existe déjà (sauf pour l'utilisateur actuel)
        if ($this->userModel->emailExists($email, $user['id'])) {
            $errors[] = 'Cet email est déjà utilisé.';
        }
        
        // Vérifier si le nom d'utilisateur existe déjà (sauf pour l'utilisateur actuel)
        if ($this->userModel->usernameExists($username, $user['id'])) {
            $errors[] = 'Ce nom d\'utilisateur est déjà utilisé.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: index.php?action=profile');
            exit;
        }
        
        $userData = [
            'username' => $username,
            'email' => $email,
            'bio' => trim($_POST['bio'] ?? '')
        ];
        
        if ($this->userModel->updateProfile($user['id'], $userData)) {
            $_SESSION['success'] = 'Profil mis à jour avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour du profil.';
        }
        
        header('Location: index.php?action=profile');
        exit;
    }
    
    /**
     * Traiter le changement de mot de passe
     */
    public function changePassword() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        // Vérifier l'ancien mot de passe
        $currentUser = $this->userModel->getByIdWithPassword($user['id']);
        if (!$currentUser || !password_verify($currentPassword, $currentUser['password'])) {
            $errors[] = 'Mot de passe actuel incorrect.';
        }
        
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $errors[] = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Les nouveaux mots de passe ne correspondent pas.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: index.php?action=profile');
            exit;
        }
        
        if ($this->userModel->updatePassword($user['id'], $newPassword)) {
            $_SESSION['success'] = 'Mot de passe modifié avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la modification du mot de passe.';
        }
        
        header('Location: index.php?action=profile');
        exit;
    }
    
    /**
     * Traiter l'upload de la photo de profil
     */
    public function uploadPhoto() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        
        // Vérifier si un fichier a été uploadé
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Veuillez sélectionner une image.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        $file = $_FILES['profile_picture'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileType = $file['type'];
        
        // Vérifier la taille (max 2MB)
        if ($fileSize > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'L\'image est trop volumineuse. Taille maximum : 2MB.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le type de fichier
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = 'Format d\'image non supporté. Utilisez JPG, PNG ou GIF.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Générer un nom de fichier unique
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = 'profile_' . $user['id'] . '_' . time() . '.' . $extension;
        $uploadPath = __DIR__ . '/../public/uploads/' . $newFileName;
        
        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Supprimer l'ancienne photo si elle existe
        $currentUser = $this->userModel->getById($user['id']);
        if (!empty($currentUser['profile_picture'])) {
            $oldPhotoPath = $uploadDir . $currentUser['profile_picture'];
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        }
        
        // Uploader la nouvelle photo
        if (move_uploaded_file($fileTmp, $uploadPath)) {
            // Mettre à jour la base de données
            if ($this->userModel->updateProfilePicture($user['id'], $newFileName)) {
                // Mettre à jour la session avec les nouvelles données utilisateur
                $updatedUser = $this->userModel->getById($user['id']);
                $_SESSION['user_data'] = $updatedUser;
                
                $_SESSION['success'] = 'Photo de profil mise à jour avec succès !';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour de la photo de profil.';
                // Supprimer le fichier uploadé en cas d'erreur
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
            }
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image.';
        }
        
        header('Location: index.php?action=profile');
        exit;
    }
    
    /**
     * Mettre à jour la question de sécurité
     */
    public function updateSecurityQuestion() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le token CSRF
        if (!$this->authController->verifyCSRFToken()) {
            $_SESSION['error'] = 'Erreur de sécurité. Veuillez réessayer.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        $user = $this->authController->getCurrentUser();
        $currentPassword = $_POST['current_password'] ?? '';
        $question = trim($_POST['security_question'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');
        
        $errors = [];
        
        if (empty($currentPassword)) {
            $errors[] = 'Le mot de passe actuel est requis.';
        }
        
        if (empty($question)) {
            $errors[] = 'La question de sécurité est requise.';
        }
        
        if (empty($answer)) {
            $errors[] = 'La réponse de sécurité est requise.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Vérifier le mot de passe actuel
        $currentUser = $this->userModel->getByIdWithPassword($user['id']);
        if (!$currentUser || !password_verify($currentPassword, $currentUser['password'])) {
            $_SESSION['error'] = 'Mot de passe actuel incorrect.';
            header('Location: index.php?action=profile');
            exit;
        }
        
        // Mettre à jour la question de sécurité
        if ($this->userModel->updateSecurityQuestion($user['id'], $question, $answer)) {
            $_SESSION['success'] = 'Question de sécurité mise à jour avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour de la question de sécurité.';
        }
        
        header('Location: index.php?action=profile');
        exit;
    }
} 