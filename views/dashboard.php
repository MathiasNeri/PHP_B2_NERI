<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <i class="fas fa-briefcase"></i> <?= APP_NAME ?>
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php 
                        $currentUser = $authController->getCurrentUser();
                        $userModel = new User();
                        $userWithPhoto = $userModel->getById($currentUser['id']);
                        ?>
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        <?= htmlspecialchars($currentUser['username']) ?>
                        <?php if ($authController->isAdmin()): ?>
                            <span class="badge bg-warning text-dark ms-1">Admin</span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.php?action=profile">
                            <i class="fas fa-user-edit"></i> Mon Profil
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?action=logout">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 