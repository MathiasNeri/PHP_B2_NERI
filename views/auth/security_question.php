<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question de sécurité - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-info">
                                <i class="fas fa-shield-alt"></i>
                            </h2>
                            <h4>Question de sécurité pour récupération de mot de passe</h4>
                            <p class="text-muted">Répondez à votre question de sécurité pour récupérer votre mot de passe</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=security_question">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $this->generateCSRFToken() ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-question-circle"></i> Votre question de sécurité
                                </label>
                                <div class="form-control-plaintext">
                                    <strong><?= htmlspecialchars($user['security_question']) ?></strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="security_answer" class="form-label">
                                    <i class="fas fa-key"></i> Votre réponse secrète
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="security_answer" 
                                       name="security_answer" 
                                       required>
                                <div class="form-text">
                                    Répondez exactement comme vous l'avez configuré lors de la création de votre compte.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-info btn-lg">
                                    <i class="fas fa-check"></i> Vérifier la réponse
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">
                                <a href="index.php?action=forgot_password" class="text-decoration-none">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 