<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Compétences - <?= APP_NAME ?></title>
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
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="public/uploads/<?= htmlspecialchars($user['profile_picture']) ?>" 
                             class="rounded-circle me-2" 
                             alt="Photo de profil"
                             style="width: 30px; height: 30px; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-user me-2"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($user['username']) ?>
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tools"></i> Gestion des Compétences</h2>
                    <a href="index.php?action=skills&subaction=create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter une compétence
                    </a>
                </div>
                
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Filtres par catégorie -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-filter"></i> Filtrer par catégorie</h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-category="all">
                                Toutes
                            </button>
                            <?php foreach ($categories as $category): ?>
                                <button type="button" class="btn btn-outline-primary" data-category="<?= htmlspecialchars($category) ?>">
                                    <?= htmlspecialchars($category) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Liste des compétences -->
                <div class="card shadow">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Liste des compétences</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($skills)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-tools fa-3x mb-3"></i>
                                <p>Aucune compétence disponible.</p>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($skills as $skill): ?>
                                    <div class="col-md-6 col-lg-4 mb-3 skill-item" data-category="<?= htmlspecialchars($skill['category']) ?>">
                                        <div class="card h-100 border-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">
                                                        <i class="fas fa-tag"></i>
                                                        <?= htmlspecialchars($skill['name']) ?>
                                                    </h6>
                                                    <?php if ($skill['is_public']): ?>
                                                        <span class="badge bg-success">Publique</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Privée</span>
                                                    <?php endif; ?>
                                                </div>
                                                </h6>
                                                <p class="card-text text-muted">
                                                    <?= htmlspecialchars($skill['description']) ?>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-secondary">
                                                        <?= htmlspecialchars($skill['category']) ?>
                                                    </span>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?action=skills&subaction=edit&id=<?= $skill['id'] ?>" 
                                                           class="btn btn-warning" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($skill['is_public']): ?>
                                                            <a href="index.php?action=skills&subaction=make_private&id=<?= $skill['id'] ?>" 
                                                               class="btn btn-info" title="Rendre privée">
                                                                <i class="fas fa-lock"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?action=skills&subaction=make_public&id=<?= $skill['id'] ?>" 
                                                               class="btn btn-success" title="Promouvoir en publique">
                                                                <i class="fas fa-globe"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="index.php?action=skills&subaction=delete&id=<?= $skill['id'] ?>" 
                                                           class="btn btn-danger"
                                                           title="Supprimer"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="index.php?action=dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage par catégorie
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('[data-category]');
            const skillItems = document.querySelectorAll('.skill-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Mettre à jour les boutons actifs
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les éléments
                    skillItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html> 