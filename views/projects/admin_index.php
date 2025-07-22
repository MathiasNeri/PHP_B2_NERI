<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($user['username']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?action=profile">Mon Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?action=logout">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-project-diagram"></i> Gestion des Projets - Administration</h1>
                    <div>
                        <a href="index.php?action=projects" class="btn btn-outline-primary me-2">
                            <i class="fas fa-folder"></i> Mes Projets
                        </a>
                        <a href="index.php?action=projects&subaction=create" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nouveau Projet
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (empty($projects)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h3 class="text-muted">Aucun projet trouvé</h3>
                        <p class="text-muted">Aucun utilisateur n'a encore créé de projet.</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($projects as $project): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-project-diagram fa-3x text-muted"></i>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                                        <p class="card-text text-muted small">
                                            <i class="fas fa-user"></i> 
                                            <?= htmlspecialchars($project['username']) ?>
                                            <?php if ($project['role'] === 'admin'): ?>
                                                <span class="badge bg-primary ms-1">Admin</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="card-text"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                                        
                                        <?php if ($project['link']): ?>
                                            <a href="<?= htmlspecialchars($project['link']) ?>" 
                                               class="btn btn-outline-primary btn-sm" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Voir le projet
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar"></i> 
                                                <?= date('d/m/Y', strtotime($project['created_at'])) ?>
                                            </small>
                                            <div class="btn-group" role="group">
                                                <?php if ($project['user_id'] == $user['id']): ?>
                                                    <a href="index.php?action=projects&subaction=edit&id=<?= $project['id'] ?>" 
                                                       class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="confirmDelete(<?= $project['id'] ?>, '<?= htmlspecialchars($project['title']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted small">Projet d'un autre utilisateur</span>
                                                <?php endif; ?>
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
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le projet "<span id="projectTitle"></span>" ?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Supprimer</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(projectId, projectTitle) {
            document.getElementById('projectTitle').textContent = projectTitle;
            document.getElementById('confirmDelete').href = `index.php?action=projects&subaction=delete&id=${projectId}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html> 