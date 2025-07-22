<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <i class="fas fa-briefcase"></i> <?= APP_NAME ?>
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?action=dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?action=skills&subaction=user">
                    <i class="fas fa-code"></i> Mes Compétences
                </a>
                <a class="nav-link" href="index.php?action=projects">
                    <i class="fas fa-project-diagram"></i> Mes Projets
                </a>
                <a class="nav-link me-3" href="index.php?action=profile" title="Mon Profil">
                    <?php if (!empty($authController->getCurrentUser()['profile_picture'])): ?>
                        <img src="public/uploads/<?= htmlspecialchars($authController->getCurrentUser()['profile_picture']) ?>" 
                             class="rounded-circle me-2" 
                             alt="Photo de profil"
                             style="width: 30px; height: 30px; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-user me-2"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($authController->getCurrentUser()['username']) ?>
                    <?php if ($authController->isAdmin()): ?>
                        <span class="badge bg-warning text-dark ms-1">Admin</span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" href="index.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 flex-grow-1">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-tachometer-alt"></i> Tableau de bord</h4>
                    </div>
                    <div class="card-body">
                        <?php 
                        require_once __DIR__ . '/../models/User.php';
                        $userModel = new User();
                        $completion = $userModel->getProfileCompletion($authController->getCurrentUser()['id']);
                        ?>
                        
                        <?php if ($completion < 100): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Profil incomplet !</strong> Votre profil est complété à <?= $completion ?>%.
                                <br>
                                <a href="index.php?action=complete_profile" class="btn btn-warning btn-sm mt-2">
                                    <i class="fas fa-user-edit"></i> Compléter mon profil
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Bienvenue ! Vous êtes maintenant connecté.
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <?php if ($authController->isAdmin()): ?>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="fas fa-project-diagram fa-3x text-primary mb-3"></i>
                                            <h5>Mes Projets</h5>
                                            <p class="text-muted">Gérer vos projets</p>
                                            <a href="index.php?action=projects" class="btn btn-primary">Voir mes projets</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                                            <h5>Gestion des Projets</h5>
                                            <p class="text-muted">Gérer tous les projets</p>
                                            <a href="index.php?action=projects&subaction=admin" class="btn btn-warning">Gérer les projets</a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="fas fa-project-diagram fa-3x text-primary mb-3"></i>
                                            <h5>Mes Projets</h5>
                                            <p class="text-muted">Gérer vos projets</p>
                                            <a href="index.php?action=projects" class="btn btn-primary">Voir les projets</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fas fa-code fa-3x text-success mb-3"></i>
                                        <h5>Mes Compétences</h5>
                                        <p class="text-muted">Gérer vos compétences</p>
                                        <a href="index.php?action=skills&subaction=user" class="btn btn-success">Voir les compétences</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fas fa-user-edit fa-3x text-info mb-3"></i>
                                        <h5>Mon Profil</h5>
                                        <p class="text-muted">Modifier votre profil</p>
                                        <a href="index.php?action=profile" class="btn btn-info">Modifier le profil</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($authController->isAdmin()): ?>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <h5><i class="fas fa-cog"></i> Administration</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Gestion des Compétences</h6>
                                                <p class="text-muted">Ajouter, modifier ou supprimer des compétences</p>
                                                <a href="index.php?action=skills" class="btn btn-warning btn-sm">Gérer les compétences</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Gestion des Utilisateurs</h6>
                                                <p class="text-muted">Voir tous les utilisateurs du système</p>
                                                <a href="index.php?action=users" class="btn btn-secondary btn-sm">Voir les utilisateurs</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-briefcase"></i> <?= APP_NAME ?></h5>
                    <p class="text-light">Application de gestion de portfolio développée dans le cadre du projet B2 Ynov 2024/2025.</p>
                    <p class="mb-0">
                        <i class="fas fa-envelope"></i> 
                        <a href="mailto:mathias.neri@ynov.com" class="text-light text-decoration-none">mathias.neri@ynov.com</a>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6>Informations légales</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php?action=legal&page=mentions" class="text-light text-decoration-none">Mentions légales</a></li>
                        <li><a href="index.php?action=legal&page=privacy" class="text-light text-decoration-none">Politique de confidentialité</a></li>
                        <li><a href="index.php?action=legal&page=terms" class="text-light text-decoration-none">Conditions d'utilisation</a></li>
                        <li><a href="index.php?action=legal&page=gdpr" class="text-light text-decoration-none">RGPD</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-light">
                        © 2024 <?= APP_NAME ?>. Tous droits réservés. 
                        Conformément aux lois françaises et européennes en vigueur, notamment le RGPD (Règlement Général sur la Protection des Données).
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 