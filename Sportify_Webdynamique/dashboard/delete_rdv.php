<?php
session_start();
include '../includes/db.php';

// Vérification complète des droits d'accès
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Vérifie que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php");
    exit();
}

// Récupération et nettoyage des données
$date = $_POST['date'] ?? '';
$heure = $_POST['heure'] ?? '';
$client_prenom = trim($_POST['client'] ?? '');
$coach_prenom = trim($_POST['coach'] ?? '');

// Vérification des champs requis
if (empty($date) || empty($heure) || empty($client_prenom) || empty($coach_prenom)) {
    die("Erreur : données manquantes.");
}

// Trouver l'ID du client
$get_client = $conn->prepare("SELECT id FROM users WHERE prenom = ?");
$get_client->bind_param("s", $client_prenom);
$get_client->execute();
$get_client->bind_result($client_id);
if (!$get_client->fetch()) {
    die("Erreur : client introuvable.");
}
$get_client->close();

// Trouver l'ID du coach
$get_coach = $conn->prepare("SELECT id FROM users WHERE prenom = ?");
$get_coach->bind_param("s", $coach_prenom);
$get_coach->execute();
$get_coach->bind_result($coach_id);
if (!$get_coach->fetch()) {
    die("Erreur : coach introuvable.");
}
$get_coach->close();

// Suppression du rendez-vous
$stmt = $conn->prepare("DELETE FROM rdvs WHERE date_rdv = ? AND heure_rdv = ? AND client_id = ? AND coach_id = ?");
$stmt->bind_param("ssii", $date, $heure, $client_id, $coach_id);
$stmt->execute();
$stmt->close();

header("Location: admin.php?delete=success");
exit();
?>
