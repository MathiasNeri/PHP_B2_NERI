<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Projet - <?= APP_NAME ?></title>
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

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h4><i class="fas fa-edit"></i> Modifier le Projet</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=projects&subaction=edit&id=<?= $project['id'] ?>" enctype="multipart/form-data">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i> Titre du projet *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       value="<?= htmlspecialchars($project['title']) ?>"
                                       minlength="3"
                                       required>
                                <div class="form-text">Minimum 3 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description *
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          minlength="10"
                                          required><?= htmlspecialchars($project['description']) ?></textarea>
                                <div class="form-text">Minimum 10 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">
                                    <i class="fas fa-link"></i> Lien du projet (optionnel)
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="link" 
                                       name="link" 
                                       value="<?= htmlspecialchars($project['link'] ?? '') ?>"
                                       placeholder="https://github.com/username/project">
                                <div class="form-text">URL vers le projet (GitHub, site web, etc.)</div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="fas fa-image"></i> Image du projet
                                </label>
                                
                                <div class="mb-2">
                                    <div class="bg-light d-inline-flex align-items-center justify-content-center" 
                                         style="width: 200px; height: 150px; border: 1px solid #dee2e6;">
                                        <i class="fas fa-project-diagram fa-2x text-muted"></i>
                                    </div>
                                    <div class="form-text">Aperçu du projet</div>
                                </div>
                                
                                <input type="file" 
                                       class="form-control" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text">
                                    Formats acceptés : JPG, PNG, GIF. Taille max : 5MB. 
                                    Laissez vide pour conserver l'image actuelle.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?action=projects" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 