<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Compétences - <?= APP_NAME ?></title>
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
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-tools"></i> Mes Compétences</h2>
                
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

                <div class="row">
                    <!-- Mes compétences actuelles -->
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-list"></i> Mes compétences actuelles</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($userSkills)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-tools fa-3x mb-3"></i>
                                        <p>Aucune compétence ajoutée pour le moment.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($userSkills as $userSkill): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-primary">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <i class="fas fa-tag"></i>
                                                            <?= htmlspecialchars($userSkill['name']) ?>
                                                        </h6>
                                                        <p class="card-text text-muted">
                                                            <?= htmlspecialchars($userSkill['description']) ?>
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-primary">
                                                                Niveau: <?= htmlspecialchars($userSkill['level']) ?>
                                                            </span>
                                                            <a href="index.php?action=skills&subaction=remove&skill_id=<?= $userSkill['id'] ?>" 
                                                               class="btn btn-danger btn-sm"
                                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Compétences privées -->
                        <?php if (!empty($privateSkills)): ?>
                            <div class="card shadow mt-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5><i class="fas fa-lock"></i> Mes compétences privées</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Note :</strong> Ces compétences sont privées et visibles uniquement par vous. 
                                        L'administrateur peut les promouvoir en compétences publiques.
                                    </div>
                                    <div class="row">
                                        <?php foreach ($privateSkills as $privateSkill): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-warning">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-tag"></i>
                                                                <?= htmlspecialchars($privateSkill['name']) ?>
                                                            </h6>
                                                            <span class="badge bg-warning text-dark">Privée</span>
                                                        </div>
                                                        <p class="card-text text-muted">
                                                            <?= htmlspecialchars($privateSkill['description']) ?>
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-secondary">
                                                                <?= htmlspecialchars($privateSkill['category']) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Ajouter une compétence -->
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5><i class="fas fa-plus"></i> Ajouter une compétence</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="index.php?action=skills&subaction=add">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $authController->generateCSRFToken() ?>">
                                    
                                    <div class="mb-3">
                                        <label for="skill_id" class="form-label">
                                            <i class="fas fa-tag"></i> Compétence
                                        </label>
                                        <select class="form-select" id="skill_id" name="skill_id" onchange="toggleCustomSkill()">
                                            <option value="">Choisir une compétence...</option>
                                            <?php foreach ($availableSkills as $skill): ?>
                                                <?php 
                                                $alreadyAdded = false;
                                                foreach ($userSkills as $userSkill) {
                                                    if ($userSkill['id'] == $skill['id']) {
                                                        $alreadyAdded = true;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <?php if (!$alreadyAdded): ?>
                                                    <option value="<?= $skill['id'] ?>">
                                                        <?= htmlspecialchars($skill['name']) ?> 
                                                        (<?= htmlspecialchars($skill['category']) ?>)
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <option value="custom">➕ Autre (compétence personnalisée)</option>
                                        </select>
                                    </div>

                                    <!-- Champs pour compétence personnalisée -->
                                    <div id="customSkillFields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="custom_skill_name" class="form-label">
                                                <i class="fas fa-plus"></i> Nom de la compétence
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="custom_skill_name" 
                                                   name="custom_skill_name" 
                                                   pattern="[A-Za-zÀ-ÿ\s\-\.]+"
                                                   title="Lettres, espaces, tirets et points uniquement"
                                                   maxlength="50">
                                            <div class="form-text">Lettres, espaces, tirets et points uniquement (max 50 caractères)</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="custom_skill_description" class="form-label">
                                                <i class="fas fa-align-left"></i> Description
                                            </label>
                                            <textarea class="form-control" 
                                                      id="custom_skill_description" 
                                                      name="custom_skill_description" 
                                                      rows="2"
                                                      maxlength="200"></textarea>
                                            <div class="form-text">Description courte (max 200 caractères)</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="custom_skill_category" class="form-label">
                                                <i class="fas fa-folder"></i> Catégorie
                                            </label>
                                            <select class="form-select" id="custom_skill_category" name="custom_skill_category">
                                                <option value="Langage de programmation">Langage de programmation</option>
                                                <option value="Framework">Framework</option>
                                                <option value="Base de données">Base de données</option>
                                                <option value="Outils de développement">Outils de développement</option>
                                                <option value="Design">Design</option>
                                                <option value="DevOps">DevOps</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="level" class="form-label">
                                            <i class="fas fa-star"></i> Niveau
                                        </label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="">Choisir un niveau...</option>
                                            <option value="Débutant">Débutant</option>
                                            <option value="Intermédiaire">Intermédiaire</option>
                                            <option value="Avancé">Avancé</option>
                                            <option value="Expert">Expert</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                        <i class="fas fa-plus"></i> Ajouter la compétence
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
        function toggleCustomSkill() {
            const skillSelect = document.getElementById('skill_id');
            const customFields = document.getElementById('customSkillFields');
            const levelSelect = document.getElementById('level');
            
            if (skillSelect.value === 'custom') {
                customFields.style.display = 'block';
                // Rendre les champs personnalisés obligatoires
                document.getElementById('custom_skill_name').required = true;
                document.getElementById('custom_skill_description').required = true;
                document.getElementById('custom_skill_category').required = true;
                // Réactiver le niveau pour les compétences personnalisées
                levelSelect.disabled = false;
            } else {
                customFields.style.display = 'none';
                // Désactiver les champs personnalisés
                document.getElementById('custom_skill_name').required = false;
                document.getElementById('custom_skill_description').required = false;
                document.getElementById('custom_skill_category').required = false;
                // Réactiver le niveau
                levelSelect.disabled = false;
            }
        }

        // Validation du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const skillSelect = document.getElementById('skill_id');
            const customName = document.getElementById('custom_skill_name');
            const customDesc = document.getElementById('custom_skill_description');
            
            if (skillSelect.value === 'custom') {
                if (!customName.value.trim() || !customDesc.value.trim()) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs pour la compétence personnalisée.');
                    return false;
                }
                
                // Validation du nom (lettres, espaces, tirets, points uniquement)
                const namePattern = /^[A-Za-zÀ-ÿ\s\-\.]+$/;
                if (!namePattern.test(customName.value.trim())) {
                    e.preventDefault();
                    alert('Le nom de la compétence ne peut contenir que des lettres, espaces, tirets et points.');
                    return false;
                }
            } else if (!skillSelect.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une compétence.');
                return false;
            }
        });
    </script>
</body>
</html> 