<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php'; // Assurez-vous que ce fichier configure correctement la connexion à la base de données

// Inclure PHPMailer via Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Ne pas trimmer le mot de passe
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
        exit();
    }
    $stmt->close();

    // Générer un code de vérification
    $verification_code = rand(100000, 999999);

    // Sauvegarder temporairement les infos dans la session
    $_SESSION['temp_user'] = [
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT), // Hasher le mot de passe
        'verification_code' => $verification_code
    ];

    // Envoyer l'email avec PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configurer le SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'abdououali708@gmail.com'; // Votre adresse Gmail
        $mail->Password = 'qmtv uvfp thak yaxu'; // Mot de passe d'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Paramètres de l'email
        $mail->setFrom('abdououali708@gmail.com', 'Service de Vérification');
        $mail->addAddress($email); // Destinataire
        $mail->Subject = "Code de vérification";
        $mail->Body = "Votre code de vérification est : " . $verification_code;

        // Envoyer l'email
        $mail->send();
        echo "Email envoyé avec succès !";
        header("Location: verify.php"); // Rediriger vers la page de vérification
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
    }
}
?>