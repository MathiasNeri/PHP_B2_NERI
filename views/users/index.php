<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - <?= APP_NAME ?></title>
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
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users"></i> Gestion des Utilisateurs</h2>
                    <a href="index.php?action=users&subaction=create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un utilisateur
                    </a>
                </div>
                
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

                <div class="card shadow">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Liste des utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($users)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Aucun utilisateur trouvé.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-id-card"></i> ID</th>
                                            <th><i class="fas fa-user"></i> Nom d'utilisateur</th>
                                            <th><i class="fas fa-envelope"></i> Email</th>
                                            <th><i class="fas fa-shield-alt"></i> Rôle</th>
                                            <th><i class="fas fa-calendar"></i> Date d'inscription</th>
                                            <th><i class="fas fa-cogs"></i> Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $userItem): ?>
                                            <tr>
                                                <td><?= $userItem['id'] ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($userItem['username']) ?></strong>
                                                    <?php if ($userItem['id'] == $user['id']): ?>
                                                        <span class="badge bg-info">Vous</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($userItem['email']) ?></td>
                                                <td>
                                                    <?php if ($userItem['role'] === 'admin'): ?>
                                                        <span class="badge bg-warning text-dark">Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Utilisateur</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($userItem['created_at'])) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?action=users&subaction=edit&id=<?= $userItem['id'] ?>" 
                                                           class="btn btn-warning" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($userItem['id'] != $user['id']): ?>
                                                            <a href="index.php?action=users&subaction=delete&id=<?= $userItem['id'] ?>" 
                                                               class="btn btn-danger" 
                                                               title="Supprimer"
                                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
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
</body>
</html> 