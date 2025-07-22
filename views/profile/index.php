<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - <?= APP_NAME ?></title>
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
                <a class="nav-link me-3" href="index.php?action=dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (!empty($userWithSecurity['profile_picture'])): ?>
                            <img src="public/uploads/<?= htmlspecialchars($userWithSecurity['profile_picture']) ?>" 
                                 alt="Photo de profil" 
                                 class="rounded-circle me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($user['username']) ?>
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
                <h2><i class="fas fa-user-edit"></i> Mon Profil</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Photo de profil -->
                    <div class="col-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-camera"></i> Photo de profil</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($userWithSecurity['profile_picture'])): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($userWithSecurity['profile_picture']) ?>" 
                                         alt="Photo de profil" 
                                         class="rounded-circle mb-3" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #007bff;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 150px; height: 150px; border: 3px solid #dee2e6;">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="index.php?action=profile&subaction=upload_photo" enctype="multipart/form-data">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">
                                            <i class="fas fa-upload"></i> Changer la photo de profil
                                        </label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="profile_picture" 
                                               name="profile_picture" 
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        <div class="form-text">
                                            Formats acceptés : JPG, PNG, GIF. Taille max : 2MB.
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Uploader la photo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du profil -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-user"></i> Informations du profil</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="index.php?action=profile&subaction=update">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            <i class="fas fa-user"></i> Nom d'utilisateur
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               value="<?= htmlspecialchars($user['username']) ?>"
                                               minlength="3"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i> Email
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?= htmlspecialchars($user['email']) ?>"
                                               autocomplete="email"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">
                                            <i class="fas fa-quote-left"></i> Bio
                                        </label>
                                        <textarea class="form-control" 
                                                  id="bio" 
                                                  name="bio" 
                                                  rows="3" 
                                                  placeholder="Parlez-nous de vous..."><?= htmlspecialchars($userWithSecurity['bio'] ?? '') ?></textarea>
                                        <div class="form-text">
                                            Décrivez-vous en quelques mots.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-calendar"></i> Date d'inscription
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>"
                                               readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-shield-alt"></i> Rôle
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?= ucfirst($user['role']) ?>"
                                               readonly>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Mettre à jour le profil
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Question de sécurité -->
                        <div class="card shadow mt-4">
                            <div class="card-header">
                                <h5><i class="fas fa-shield-alt"></i> Question de sécurité pour récupération de mot de passe</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="index.php?action=profile&subaction=security_question">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="security_question" class="form-label">
                                            <i class="fas fa-question-circle"></i> Votre question de sécurité
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="security_question" 
                                               name="security_question" 
                                               value="<?= htmlspecialchars($userWithSecurity['security_question'] ?? '') ?>"
                                               placeholder="Ex: Quel est le nom de votre premier animal de compagnie ?"
                                               required>
                                        <div class="form-text">
                                            <i class="fas fa-question-circle text-info"></i> 
                                            Cette question sera utilisée pour récupérer votre mot de passe en cas d'oubli. Choisissez une question personnelle dont vous seul connaissez la réponse.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="security_answer" class="form-label">
                                            <i class="fas fa-key"></i> Votre réponse secrète
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="security_answer" 
                                               name="security_answer" 
                                               placeholder="Votre réponse secrète"
                                               autocomplete="new-password"
                                               data-lpignore="true"
                                               data-form-type="other"
                                               required>
                                        <div class="form-text">
                                            <i class="fas fa-shield-alt text-success"></i> 
                                            Cette réponse sera <strong>hachée et sécurisée</strong> et ne sera jamais affichée en clair. Elle vous permettra de récupérer votre mot de passe.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="security_current_password" class="form-label">
                                            <i class="fas fa-key"></i> Mot de passe actuel
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="security_current_password" 
                                               name="current_password" 
                                               autocomplete="current-password"
                                               required>
                                        <div class="form-text">
                                            Confirmez votre mot de passe pour modifier la question de sécurité de récupération de mot de passe.
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-shield-alt"></i> Mettre à jour la question de sécurité de récupération
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Changement de mot de passe -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-lock"></i> Changer le mot de passe</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="index.php?action=profile&subaction=password">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">
                                            <i class="fas fa-key"></i> Mot de passe actuel
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="current_password" 
                                               name="current_password" 
                                               autocomplete="current-password"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">
                                            <i class="fas fa-lock"></i> Nouveau mot de passe
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password" 
                                               name="new_password" 
                                               minlength="6"
                                               autocomplete="new-password"
                                               required>
                                        <div class="form-text">Minimum 6 caractères</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">
                                            <i class="fas fa-lock"></i> Confirmer le nouveau mot de passe
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               autocomplete="new-password"
                                               required>
                                    </div>

                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key"></i> Changer le mot de passe
                                    </button>
                                </form>
                            </div>
                        </div>
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
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'rounded-circle mb-3';
                    preview.style = 'width: 150px; height: 150px; object-fit: cover; border: 3px solid #007bff;';
                    preview.alt = 'Aperçu';
                    
                    const container = input.closest('.card-body');
                    const existingPreview = container.querySelector('img[alt="Aperçu"]');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    const existingPlaceholder = container.querySelector('.bg-light.rounded-circle');
                    if (existingPlaceholder) {
                        existingPlaceholder.remove();
                    }
                    
                    container.insertBefore(preview, input.closest('.mb-3'));
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html> 