<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

// Traitement annulation de rendez-vous
if (isset($_GET['cancel_id']) && is_numeric($_GET['cancel_id'])) {
    $rdv_id = (int)$_GET['cancel_id'];

    $stmt_get = $conn->prepare("SELECT coach_id, date_rdv, heure_rdv FROM rdvs WHERE id = ? AND client_id = ?");
    $stmt_get->bind_param("ii", $rdv_id, $client_id);
    $stmt_get->execute();
    $rdv_data = $stmt_get->get_result()->fetch_assoc();

    if ($rdv_data) {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $jour = strtolower(strftime('%A', strtotime($rdv_data['date_rdv'])));

        $stmt_dispo = $conn->prepare("
            UPDATE disponibilites 
            SET disponible = 1 
            WHERE coach_id = ? AND heure = ? AND jour = ?
        ");
        $stmt_dispo->bind_param("iss", $rdv_data['coach_id'], $rdv_data['heure_rdv'], $jour);
        $stmt_dispo->execute();

        $stmt_del = $conn->prepare("DELETE FROM rdvs WHERE id = ?");
        $stmt_del->bind_param("i", $rdv_id);
        $stmt_del->execute();
    }

    header("Location: client.php?success=rdv_cancelled");
    exit();
}

// 🔁 Correction ici : jointure correcte pour récupérer le nom/prénom du coach
$stmt = $conn->prepare("
    SELECT rdvs.id, rdvs.date_rdv, rdvs.heure_rdv, users.nom AS coach_nom, users.prenom AS coach_prenom
    FROM rdvs
    JOIN coachs ON rdvs.coach_id = coachs.id
    JOIN users ON coachs.user_id = users.id
    WHERE rdvs.client_id = ?
    ORDER BY rdvs.date_rdv, rdvs.heure_rdv
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <a href="javascript:history.back()" class="btn btn-outline-secondary mt-3 mb-3">⬅ Retour</a>

    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] === 'rdv_booked'): ?>
            <div class="alert alert-success">✅ Rendez-vous réservé avec succès !</div>
        <?php elseif ($_GET['success'] === 'rdv_cancelled'): ?>
            <div class="alert alert-info">ℹ️ Rendez-vous annulé.</div>
        <?php endif; ?>
    <?php endif; ?>

    <h2>👤 Bienvenue, <?= htmlspecialchars($_SESSION['prenom']) ?></h2>
    <h4 class="mt-4">📆 Mes rendez-vous</h4>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Coach</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['coach_prenom'] . ' ' . $row['coach_nom']) ?></td>
                        <td><?= htmlspecialchars($row['date_rdv']) ?></td>
                        <td><?= substr($row['heure_rdv'], 0, 5) ?></td>
                        <td>
                            <a href="?cancel_id=<?= $row['id'] ?>"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Voulez-vous annuler ce rendez-vous ?')">
                               ❌ Annuler
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning mt-3">Vous n'avez aucun rendez-vous prévu.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
