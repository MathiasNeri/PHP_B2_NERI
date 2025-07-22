<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Compétence - <?= APP_NAME ?></title>
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
                     <?= htmlspecialchars($user['username']) ?>
                    <?php if ($authController->isAdmin()): ?>
                        <span class="badge bg-warning text-dark">Admin
                </a>
                    <?php endif; ?>
                </span>
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
                        <h3><i class="fas fa-plus"></i> Ajouter une nouvelle compétence</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=skills&subaction=create">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-tag"></i> Nom de la compétence
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                       minlength="2"
                                       required>
                                <div class="form-text">Minimum 2 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          minlength="5"
                                          required><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                <div class="form-text">Minimum 5 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-folder"></i> Catégorie
                                </label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Choisir une catégorie...</option>
                                    <option value="Langage de programmation" <?= ($formData['category'] ?? '') === 'Langage de programmation' ? 'selected' : '' ?>>
                                        Langage de programmation
                                    </option>
                                    <option value="Framework" <?= ($formData['category'] ?? '') === 'Framework' ? 'selected' : '' ?>>
                                        Framework
                                    </option>
                                    <option value="Base de données" <?= ($formData['category'] ?? '') === 'Base de données' ? 'selected' : '' ?>>
                                        Base de données
                                    </option>
                                    <option value="Outils de développement" <?= ($formData['category'] ?? '') === 'Outils de développement' ? 'selected' : '' ?>>
                                        Outils de développement
                                    </option>
                                    <option value="Design" <?= ($formData['category'] ?? '') === 'Design' ? 'selected' : '' ?>>
                                        Design
                                    </option>
                                    <option value="DevOps" <?= ($formData['category'] ?? '') === 'DevOps' ? 'selected' : '' ?>>
                                        DevOps
                                    </option>
                                    <option value="Autre" <?= ($formData['category'] ?? '') === 'Autre' ? 'selected' : '' ?>>
                                        Autre
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?action=skills" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Créer la compétence
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