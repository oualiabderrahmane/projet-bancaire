<?php
session_start();
if (!isset($_SESSION['temp_user'])) {
    header("Location: signup.php");
    exit();
}

include 'header.php'; // Inclure l'en-tête
?>

<link rel="stylesheet" href="verify.css">
<title>Vérification du Code</title>

<div class="container">
    <h2>Vérification du Code</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $entered_code = $_POST['code'];

        if ($entered_code == $_SESSION['temp_user']['verification_code']) {
            include 'db.php';

            // Insérer dans `users`
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $_SESSION['temp_user']['email'], $_SESSION['temp_user']['password']);
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $stmt->close();

                // Insérer dans `user_details`
                $stmt = $conn->prepare("INSERT INTO user_details (user_id, name, surname) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $_SESSION['temp_user']['name'], $_SESSION['temp_user']['surname']);
                $stmt->execute();
                $stmt->close();

                // Connecter l'utilisateur en créant une session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $_SESSION['temp_user']['email'];
                $_SESSION['user_name'] = $_SESSION['temp_user']['name'];

                // Supprimer les données temporaires
                unset($_SESSION['temp_user']);

                // Rediriger vers le profil
                header("Location: profile.php");
                exit();
            } else {
                echo "<p class='error'>❌ Erreur lors de l'inscription.</p>";
            }
        } else {
            echo "<p class='error'>❌ Code incorrect. Essayez encore.</p>";
        }
    }
    ?>

    <form method="POST">
        <label>Entrez le code reçu :</label>
        <input type="text" name="code" required>
        <button type="submit">Vérifier</button>
    </form>
</div>

<?php include 'footer.php'; // Inclure le pied de page ?>
