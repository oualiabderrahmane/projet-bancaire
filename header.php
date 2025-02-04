<?php
// Vérifier si une session n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Initialisation de l'utilisateur
$user = null;

if (isset($_SESSION['user_id'])) {
    // Récupérer les informations utilisateur depuis `users` et `user_details`
    $stmt = $conn->prepare("
        SELECT u.email, d.name, d.surname 
        FROM users u 
        JOIN user_details d ON u.id = d.user_id 
        WHERE u.id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Bancaire</title>
    <link rel="stylesheet" href="projetcs.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <header>
        <h1>Bienvenue sur notre interface bancaire</h1>
    </header>

    <nav>
        <a href="home.php">Accueil</a>
        <a href="services.php">Services</a>
        <a href="cheque.php">Chèque bancaire</a>
        <a href="tarifs.php">Nos tarifs</a>
        <a href="virement.php">Virement</a>
        <a href="convertisseur.php">Convertisseur de devises</a>
        <a href="appel.php">Appel d'offres</a>

        <!-- Icône de profil -->
        <?php if ($user): ?>
            <div class="profile-menu">
                <img src="profile-icon.png" alt="Profile" id="profile-icon">
                <div class="dropdown-content">
                    <p><strong><?php echo htmlspecialchars($user['name'] . " " . $user['surname']); ?></strong></p>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <a href="profile.php">Mon Profil</a>
                    <a href="logout.php">Se déconnecter</a>
                </div>
            </div>
        <?php else: ?>
            <a href="signin.php">Connexion</a>
            <a href="signup.php">Inscription</a>
        <?php endif; ?>
    </nav>
</body>
</html>
