<?php
/**
 * Point d'entrée principal - Portfolio PHP/MVC
 * Projet B2
 */

// Charger la configuration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/EncodingHelper.php';

// Initialiser l'encodage UTF-8 automatiquement
EncodingHelper::initUTF8();

// Corriger automatiquement les données d'entrée
EncodingHelper::fixInputData();

// Charger les contrôleurs
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProjectController.php';
require_once __DIR__ . '/controllers/SkillController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/UserController.php';

// Initialiser les contrôleurs
$authController = new AuthController();
$projectController = new ProjectController();
$skillController = new SkillController();
$profileController = new ProfileController();
$userController = new UserController();

// Récupérer l'action depuis l'URL
$action = $_GET['action'] ?? 'login';

// Router les actions
switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->loginForm();
        }
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->registerForm();
        }
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'forgot_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->forgotPassword();
        } else {
            $authController->forgotPasswordForm();
        }
        break;
        
    case 'security_question':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->securityQuestion();
        } else {
            $authController->securityQuestionForm();
        }
        break;
        
    case 'reset_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->resetPassword();
        } else {
            $authController->resetPasswordForm();
        }
        break;
        
    case 'complete_profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->completeProfile();
        } else {
            $authController->completeProfileForm();
        }
        break;
        
    case 'dashboard':
        $authController->requireAuth();
        include __DIR__ . '/views/dashboard.php';
        break;
        
    // Gestion des projets
    case 'projects':
        $subAction = $_GET['subaction'] ?? 'index';
        $id = $_GET['id'] ?? null;
        
        switch ($subAction) {
            case 'index':
                $projectController->index();
                break;
            case 'admin':
                $projectController->adminIndex();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $projectController->create();
                } else {
                    $projectController->createForm();
                }
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $projectController->edit($id);
                } else {
                    $projectController->editForm($id);
                }
                break;
            case 'delete':
                $projectController->delete($id);
                break;
            default:
                $projectController->index();
        }
        break;
        
    // Gestion des compétences
    case 'skills':
        $subAction = $_GET['subaction'] ?? 'index';
        $id = $_GET['id'] ?? null;
        $skillId = $_GET['skill_id'] ?? null;
        
        switch ($subAction) {
            case 'index':
                $skillController->index();
                break;
            case 'user':
                $skillController->userSkills();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $skillController->create();
                } else {
                    $skillController->createForm();
                }
                break;
            case 'add':
                $skillController->addToUser();
                break;
            case 'remove':
                $skillController->removeFromUser();
                break;
            case 'delete':
                $skillController->delete();
                break;
            case 'make_public':
                $skillController->makePublic();
                break;
            case 'make_private':
                $skillController->makePrivate();
                break;
            default:
                $skillController->index();
        }
        break;
        
    // Gestion du profil
    case 'profile':
        $subAction = $_GET['subaction'] ?? 'index';
        
        switch ($subAction) {
            case 'index':
                $profileController->index();
                break;
            case 'update':
                $profileController->update();
                break;
            case 'upload_photo':
                $profileController->uploadPhoto();
                break;
            case 'password':
                $profileController->changePassword();
                break;
            case 'security_question':
                $profileController->updateSecurityQuestion();
                break;
            default:
                $profileController->index();
        }
        break;
        
    // Gestion des utilisateurs (admin)
    case 'users':
        $subAction = $_GET['subaction'] ?? 'index';
        $id = $_GET['id'] ?? null;
        
        switch ($subAction) {
            case 'index':
                $userController->index();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userController->create();
                } else {
                    $userController->createForm();
                }
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userController->edit($id);
                } else {
                    $userController->editForm($id);
                }
                break;
            case 'delete':
                $userController->delete($id);
                break;
            default:
                $userController->index();
        }
        break;
        
    default:
        // Rediriger vers la page de connexion par défaut
        header('Location: index.php?action=login');
        exit;
} 