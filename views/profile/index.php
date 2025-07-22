<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - <?= APP_NAME ?></title>
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

    <div class="container mt-4 flex-grow-1">
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
                                <?php if (!empty($user['profile_picture'])): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($user['profile_picture']) ?>" 
                                         class="rounded-circle mb-3" 
                                         alt="Photo de profil"
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

                    <!-- Informations personnelles -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-user"></i> Informations personnelles</h5>
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
                                               value="<?= htmlspecialchars($user['email']) ?>"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">
                                            <i class="fas fa-quote-left"></i> Bio
                                        </label>
                                        <textarea class="form-control" 
                                                  id="bio" 
                                                  name="bio" 
                                                  rows="4" 
                                                  placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                                        <div class="form-text">Décrivez-vous en quelques mots</div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Sauvegarder les modifications
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

                <!-- Question de sécurité -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-question-circle"></i> Question de sécurité</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="index.php?action=profile&subaction=security_question">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="current_password_security" class="form-label">
                                            <i class="fas fa-key"></i> Mot de passe actuel
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="current_password_security" 
                                               name="current_password" 
                                               autocomplete="current-password"
                                               required>
                                        <div class="form-text">Requis pour modifier la question de sécurité</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="security_question" class="form-label">
                                            <i class="fas fa-question-circle"></i> Votre question de sécurité
                                        </label>
                                        <select class="form-control" 
                                                id="security_question" 
                                                name="security_question" 
                                                required>
                                            <option value="">Choisissez une question...</option>
                                            <option value="Quel est le nom de votre premier animal de compagnie ?" 
                                                    <?= ($userWithSecurity['security_question'] ?? '') === 'Quel est le nom de votre premier animal de compagnie ?' ? 'selected' : '' ?>>
                                                Quel est le nom de votre premier animal de compagnie ?
                                            </option>
                                            <option value="Dans quelle ville êtes-vous né(e) ?" 
                                                    <?= ($userWithSecurity['security_question'] ?? '') === 'Dans quelle ville êtes-vous né(e) ?' ? 'selected' : '' ?>>
                                                Dans quelle ville êtes-vous né(e) ?
                                            </option>
                                            <option value="Quel est le nom de votre mère ?" 
                                                    <?= ($userWithSecurity['security_question'] ?? '') === 'Quel est le nom de votre mère ?' ? 'selected' : '' ?>>
                                                Quel est le nom de votre mère ?
                                            </option>
                                            <option value="Quel était votre premier métier ?" 
                                                    <?= ($userWithSecurity['security_question'] ?? '') === 'Quel était votre premier métier ?' ? 'selected' : '' ?>>
                                                Quel était votre premier métier ?
                                            </option>
                                            <option value="Quel est le nom de votre école primaire ?" 
                                                    <?= ($userWithSecurity['security_question'] ?? '') === 'Quel est le nom de votre école primaire ?' ? 'selected' : '' ?>>
                                                Quel est le nom de votre école primaire ?
                                            </option>
                                        </select>
                                        <div class="form-text">
                                            Choisissez une question personnelle dont vous seul connaissez la réponse. Cette question sera utilisée pour récupérer votre mot de passe.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="security_answer" class="form-label">
                                            <i class="fas fa-key"></i> Votre réponse
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="security_answer" 
                                               name="security_answer" 
                                               placeholder="Entrez votre réponse"
                                               required>
                                        <div class="form-text">Votre réponse à la question de sécurité</div>
                                    </div>

                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-save"></i> Mettre à jour la question de sécurité
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
                    
                    // Supprimer l'aperçu existant
                    const existingPreview = container.querySelector('img[alt="Aperçu"]');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Supprimer la photo existante ou le placeholder
                    const existingPhoto = container.querySelector('img[alt="Photo de profil"]');
                    if (existingPhoto) {
                        existingPhoto.remove();
                    }
                    
                    const existingPlaceholder = container.querySelector('.bg-light.rounded-circle');
                    if (existingPlaceholder) {
                        existingPlaceholder.remove();
                    }
                    
                    // Insérer la nouvelle preview
                    container.insertBefore(preview, input.closest('.mb-3'));
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Recharger la page après un upload réussi
        <?php if (!empty($_SESSION['success']) && strpos($_SESSION['success'], 'photo') !== false): ?>
        setTimeout(function() {
            window.location.reload();
        }, 1000);
        <?php endif; ?>
    </script>
</body>
</html> 