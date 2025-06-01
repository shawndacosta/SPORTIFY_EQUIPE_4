<?php
$host = 'localhost';
$db = 'sportify_database';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Vérifie la connexion
if ($conn->connect_error) {
    die("❌ Connexion échouée : " . $conn->connect_error);
}

// Pour forcer UTF-8 (utile si problèmes d’accents)
$conn->set_charset("utf8mb4");
?>
