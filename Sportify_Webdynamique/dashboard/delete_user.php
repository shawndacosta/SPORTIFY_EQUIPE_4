<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin' || !isset($_POST['user_id'])) {
    header("Location: admin.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$user_id = intval($_POST['user_id']);

// Empêche la suppression de soi-même
if ($user_id === $admin_id) {
    header("Location: admin.php?error=self-delete");
    exit();
}

// Supprimer les RDVs liés (client ou coach)
$stmt = $conn->prepare("DELETE FROM rdvs WHERE coach_id = ? OR client_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();

// Supprimer l'utilisateur
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

header("Location: admin.php?success=deleted");
exit();
?>
