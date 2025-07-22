<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RGPD - Protection des Données - <?= APP_NAME ?></title>
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
                        <h2><i class="fas fa-user-shield"></i> RGPD - Protection des Données</h2>
                    </div>
                    <div class="card-body">
                        <h4>Qu'est-ce que le RGPD ?</h4>
                        <p>Le Règlement Général sur la Protection des Données (RGPD) est un règlement européen qui renforce et unifie la protection des données personnelles des citoyens de l'Union européenne.</p>

                        <h4 class="mt-4">Nos engagements RGPD</h4>
                        <p>Nous nous engageons à respecter vos droits :</p>
                        <ul>
                            <li><strong>Droit d'accès :</strong> Vous pouvez demander l'accès à vos données personnelles</li>
                            <li><strong>Droit de rectification :</strong> Vous pouvez corriger vos données inexactes</li>
                            <li><strong>Droit à l'effacement :</strong> Vous pouvez demander la suppression de vos données</li>
                            <li><strong>Droit à la portabilité :</strong> Vous pouvez récupérer vos données</li>
                            <li><strong>Droit d'opposition :</strong> Vous pouvez vous opposer au traitement</li>
                        </ul>

                        <h4 class="mt-4">Données collectées</h4>
                        <p>Nous collectons uniquement les données nécessaires :</p>
                        <ul>
                            <li><strong>Données d'identification :</strong> Nom d'utilisateur, email</li>
                            <li><strong>Données de profil :</strong> Bio, photo de profil</li>
                            <li><strong>Données de connexion :</strong> Sessions, logs de sécurité</li>
                            <li><strong>Contenu utilisateur :</strong> Projets, compétences</li>
                        </ul>

                        <h4 class="mt-4">Sécurité des données</h4>
                        <p>Nous mettons en œuvre des mesures de sécurité :</p>
                        <ul>
                            <li>Hachage sécurisé des mots de passe (password_hash)</li>
                            <li>Protection contre les attaques CSRF et XSS</li>
                            <li>Requêtes préparées contre les injections SQL</li>
                            <li>Chiffrement des données sensibles</li>
                            <li>Accès restreint aux données personnelles</li>
                        </ul>

                        <h4 class="mt-4">Durée de conservation</h4>
                        <p>Vos données sont conservées :</p>
                        <ul>
                            <li>Pendant la durée de votre inscription</li>
                            <li>Jusqu'à votre demande de suppression</li>
                            <li>Conformément aux obligations légales</li>
                        </ul>

                        <h4 class="mt-4">Contact DPO</h4>
                        <p>Pour toute question concernant vos données personnelles :</p>
                        <p><strong>Email :</strong> <a href="mailto:mathias.neri@ynov.com">mathias.neri@ynov.com</a></p>

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