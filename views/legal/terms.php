<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions d'Utilisation - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <i class="fas fa-briefcase"></i> <?= APP_NAME ?>
            </a>
        </div>
    </nav>

    <div class="container mt-4 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h2><i class="fas fa-file-contract"></i> Conditions d'Utilisation</h2>
                    </div>
                    <div class="card-body">
                        <h4>Acceptation des conditions</h4>
                        <p>En utilisant cette application, vous acceptez d'être lié par ces conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser le service.</p>

                        <h4 class="mt-4">Utilisation du service</h4>
                        <p>Vous vous engagez à :</p>
                        <ul>
                            <li>Fournir des informations exactes lors de l'inscription</li>
                            <li>Protéger vos identifiants de connexion</li>
                            <li>Ne pas utiliser le service à des fins illégales</li>
                            <li>Respecter les droits de propriété intellectuelle</li>
                            <li>Ne pas perturber le fonctionnement du service</li>
                        </ul>

                        <h4 class="mt-4">Contenu utilisateur</h4>
                        <p>Vous conservez la propriété de votre contenu, mais accordez une licence d'utilisation à l'application pour :</p>
                        <ul>
                            <li>Afficher votre portfolio et projets</li>
                            <li>Gérer vos compétences</li>
                            <li>Assurer le bon fonctionnement du service</li>
                        </ul>

                        <h4 class="mt-4">Limitation de responsabilité</h4>
                        <p>L'application est fournie "en l'état" sans garantie d'aucune sorte. Nous ne pouvons garantir :</p>
                        <ul>
                            <li>La disponibilité continue du service</li>
                            <li>L'absence d'erreurs ou de bugs</li>
                            <li>La sécurité absolue des données</li>
                        </ul>

                        <h4 class="mt-4">Modifications</h4>
                        <p>Nous nous réservons le droit de modifier ces conditions à tout moment. Les modifications seront notifiées aux utilisateurs.</p>

                        <div class="text-center mt-4">
                            <a href="index.php?action=dashboard" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Retour au site
                            </a>
                        </div>
                    </div>
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
</body>
</html> 