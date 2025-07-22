<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets - <?= APP_NAME ?></title>
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
                <a class="nav-link me-3" href="index.php?action=profile" title="Mon Profil">
                    <i class="fas fa-user"></i> 
                    <?= EncodingHelper::h($user['username']) ?>
                    <?php if ($authController->isAdmin()): ?>
                        <span class="badge bg-warning text-dark">Admin</span>
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
                    <h2><i class="fas fa-project-diagram"></i> Mes Projets</h2>
                    <a href="index.php?action=projects&subaction=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Projet
                    </a>
                </div>

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?= EncodingHelper::h($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= EncodingHelper::h($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (empty($projects)): ?>
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4>Aucun projet</h4>
                            <p class="text-muted">Vous n'avez pas encore créé de projet.</p>
                            <a href="index.php?action=projects&subaction=create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer votre premier projet
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($projects as $project): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <?php if ($project['image'] && file_exists('public/uploads/' . $project['image'])): ?>
                                        <img src="public/uploads/<?= EncodingHelper::h($project['image']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= EncodingHelper::h($project['title']) ?>"
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?= EncodingHelper::h($project['title']) ?></h5>
                                        <p class="card-text text-muted">
                                            <?= EncodingHelper::h(substr($project['description'], 0, 100)) ?>...
                                        </p>
                                        
                                        <?php if ($project['link']): ?>
                                            <a href="<?= EncodingHelper::h($project['link']) ?>" 
                                               class="btn btn-outline-primary btn-sm mb-2" 
                                               target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Voir le projet
                                            </a>
                                        <?php endif; ?>
                                        
                                        <div class="text-muted small">
                                            <i class="fas fa-user"></i> 
                                            <?= EncodingHelper::h($project['username'] ?? 'Utilisateur') ?>
                                            <br>
                                            <i class="fas fa-calendar"></i> 
                                            <?= date('d/m/Y', strtotime($project['created_at'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <a href="index.php?action=projects&subaction=edit&id=<?= $project['id'] ?>" 
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    onclick="confirmDelete(<?= $project['id'] ?>)">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
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
                    Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(projectId) {
            document.getElementById('confirmDeleteBtn').href = 'index.php?action=projects&subaction=delete&id=' + projectId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html> 