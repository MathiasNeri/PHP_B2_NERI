<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compléter votre profil - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-completion {
            background: linear-gradient(90deg, #28a745 0%, #ffc107 50%, #dc3545 100%);
            height: 8px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-primary">
                                <i class="fas fa-user-edit"></i>
                            </h2>
                            <h4>Compléter votre profil</h4>
                            <p class="text-muted">Votre profil est complété à <?= $completion ?>%</p>
                            
                            <div class="profile-completion">
                                <div class="progress-bar" style="width: <?= $completion ?>%; height: 100%; background: #28a745; border-radius: 4px;"></div>
                            </div>
                        </div>

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

                        <form method="POST" action="index.php?action=complete_profile" enctype="multipart/form-data">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $this->generateCSRFToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">
                                            <i class="fas fa-camera"></i> Photo de profil
                                        </label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="profile_picture" 
                                               name="profile_picture" 
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        <div class="form-text">
                                            JPG, PNG ou GIF. Max 2MB.
                                        </div>
                                    </div>
                                    
                                    <div class="text-center mb-3">
                                        <img id="preview" src="<?= !empty($userWithSecurity['profile_picture']) ? UPLOAD_PATH . '/' . htmlspecialchars($userWithSecurity['profile_picture']) : 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\' viewBox=\'0 0 150 150\'%3E%3Crect width=\'150\' height=\'150\' fill=\'%23dee2e6\'/%3E%3Ctext x=\'75\' y=\'75\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%236c757d\'%3EAperçu%3C/text%3E%3C/svg%3E' ?>" 
                                             class="profile-picture-preview" alt="Aperçu">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">
                                            <i class="fas fa-quote-left"></i> Bio
                                        </label>
                                        <textarea class="form-control" 
                                                  id="bio" 
                                                  name="bio" 
                                                  rows="4" 
                                                  placeholder="Parlez-nous de vous..."><?= htmlspecialchars($_SESSION['form_data']['bio'] ?? $userWithSecurity['bio'] ?? '') ?></textarea>
                                        <div class="form-text">
                                            Décrivez-vous en quelques mots.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">
                                <i class="fas fa-shield-alt"></i> Question de sécurité pour récupération de mot de passe
                            </h5>
                            <p class="text-muted small">
                                Cette question vous aidera à récupérer votre mot de passe si vous l'oubliez. Elle est essentielle pour la sécurité de votre compte.
                            </p>

                            <div class="mb-3">
                                <label for="security_question" class="form-label">
                                    <i class="fas fa-question-circle"></i> Votre question de sécurité
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="security_question" 
                                       name="security_question" 
                                       value="<?= htmlspecialchars($_SESSION['form_data']['security_question'] ?? $userWithSecurity['security_question'] ?? '') ?>"
                                       placeholder="Ex: Quel est le nom de votre premier animal de compagnie ?"
                                       required>
                                <div class="form-text">
                                    Choisissez une question personnelle dont vous seul connaissez la réponse. Cette question sera utilisée pour récupérer votre mot de passe.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="security_answer" class="form-label">
                                    <i class="fas fa-key"></i> Votre réponse secrète
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="security_answer" 
                                       name="security_answer" 
                                       value="<?= htmlspecialchars($_SESSION['form_data']['security_answer'] ?? '') ?>"
                                       placeholder="Votre réponse secrète"
                                       required>
                                <div class="form-text">
                                    Cette réponse sera sécurisée et ne sera jamais affichée. Elle vous permettra de récupérer votre mot de passe.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check"></i> Compléter mon profil
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">
                                <a href="index.php?action=dashboard" class="text-decoration-none">
                                    <i class="fas fa-arrow-left"></i> Retour au dashboard
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html> 