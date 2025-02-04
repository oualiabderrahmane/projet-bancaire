<?php
$host = 'localhost';  // Serveur MySQL (généralement localhost pour XAMPP)
$username = 'root';   // Nom d'utilisateur MySQL (par défaut root sur XAMPP)
$password = '';       // Mot de passe MySQL (vide par défaut sur XAMPP)
$database = 'mypro'; // Nom de ta base de données

// Créer une connexion
$conn = new mysqli($host, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Définir l'encodage des caractères (UTF-8)
$conn->set_charset("utf8mb4");
?>
