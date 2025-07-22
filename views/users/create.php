<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur - <?= APP_NAME ?></title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h3><i class="fas fa-user-plus"></i> Ajouter un nouvel utilisateur</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=users&subaction=create">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user"></i> Nom d'utilisateur
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       value="<?= htmlspecialchars($formData['username'] ?? '') ?>"
                                       minlength="3"
                                       required>
                                <div class="form-text">Minimum 3 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i> Mot de passe
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       minlength="6"
                                       required>
                                <div class="form-text">Minimum 6 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock"></i> Confirmer le mot de passe
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-shield-alt"></i> Rôle
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user" <?= ($formData['role'] ?? '') === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                    <option value="admin" <?= ($formData['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?action=users" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Créer l'utilisateur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }
        });
    </script>
</body>
</html> 