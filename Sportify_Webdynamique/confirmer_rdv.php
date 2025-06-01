<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$dispo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$dispo_id) {
    die("❌ Créneau invalide.");
}

// Récupérer le créneau
$stmt = $conn->prepare("
    SELECT d.id, d.coach_id, d.jour, d.heure, c.bureau
    FROM disponibilites d
    JOIN coachs c ON d.coach_id = c.user_id
    WHERE d.id = ? AND d.disponible = 1
");
$stmt->bind_param("i", $dispo_id);
$stmt->execute();
$res = $stmt->get_result();
$dispo = $res->fetch_assoc();

if (!$dispo) {
    die("❌ Ce créneau n'est plus disponible.");
}

// Calcul de la date du prochain jour correspondant (ex: prochain mardi)
function prochainJour($jour) {
    $jours = ['lundi'=>1, 'mardi'=>2, 'mercredi'=>3, 'jeudi'=>4, 'vendredi'=>5, 'samedi'=>6, 'dimanche'=>0];
    $today = date('w');
    $target = $jours[strtolower($jour)];
    $delta = ($target - $today + 7) % 7;
    return date('Y-m-d', strtotime("+$delta days"));
}

$date_rdv = prochainJour($dispo['jour']);
$heure_rdv = $dispo['heure'];

// Enregistrement du rendez-vous
$insert = $conn->prepare("INSERT INTO rdvs (coach_id, client_id, date_rdv, heure_rdv) VALUES (?, ?, ?, ?)");
$insert->bind_param("iiss", $dispo['coach_id'], $client_id, $date_rdv, $heure_rdv);
$insert->execute();

// Marquer le créneau comme pris
$update = $conn->prepare("UPDATE disponibilites SET disponible = 0 WHERE id = ?");
$update->bind_param("i", $dispo_id);
$update->execute();

// Redirection
header("Location: client.php?success=rdv_booked");
exit();
