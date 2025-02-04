<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirige vers la page de connexion si non connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'utilisateur
$sql = "SELECT u.email, ud.name, ud.surname, c.account_number, c.solde 
        FROM users u 
        JOIN user_details ud ON u.id = ud.user_id 
        JOIN compte c ON u.id = c.user_id 
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo "❌ Impossible de récupérer les informations de votre compte.";
    exit;
}
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="profile.css">

<div class="main-content">
    <section class="profile-container">
        <h1>Bienvenue, <?php echo htmlspecialchars($userData['name'] . ' ' . $userData['surname']); ?> !</h1>
        <p>Email : <?php echo htmlspecialchars($userData['email']); ?></p>
        <p>Numéro de compte : <strong><?php echo htmlspecialchars($userData['account_number']); ?></strong></p>
        <p>Solde actuel : <strong><?php echo number_format($userData['solde'], 2, ',', ' '); ?> DZD</strong></p>
        <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </section>
</div>

<?php include 'footer.php'; ?>
