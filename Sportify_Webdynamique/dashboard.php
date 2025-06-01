<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning text-center mt-5'>Veuillez vous connecter pour voir vos rendez-vous.</div>";
    exit();
}

$client_id = $_SESSION['user_id'];

// Annulation de RDV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rdv_id'], $_POST['coach_id'])) {
    $rdv_id = (int)$_POST['rdv_id'];
    $coach_id = (int)$_POST['coach_id'];

    // Récupération de l'heure
    $stmt = $conn->prepare("SELECT heure_rdv, date_rdv FROM rdvs WHERE id = ?");
    $stmt->bind_param("i", $rdv_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $rdv = $res->fetch_assoc();

    if ($rdv && isset($rdv['heure_rdv'], $rdv['date_rdv'])) {
        // Récupère le jour à partir de la date
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $jour = strtolower(strftime('%A', strtotime($rdv['date_rdv'])));

        $stmt2 = $conn->prepare("UPDATE disponibilites SET disponible = 1 WHERE coach_id = ? AND heure = ? AND jour = ?");
        $stmt2->bind_param("iss", $coach_id, $rdv['heure_rdv'], $jour);
        $stmt2->execute();
        $stmt2->close();
    }

    // Supprime le RDV
    $stmt3 = $conn->prepare("DELETE FROM rdvs WHERE id = ?");
    $stmt3->bind_param("i", $rdv_id);
    $stmt3->execute();
    $stmt3->close();

    header("Location: dashboard.php?success=rdv_cancelled");
    exit();
}

// Affiche les rendez-vous du client
$stmt = $conn->prepare("
    SELECT rdvs.*, users.nom AS coach_nom, users.prenom AS coach_prenom 
    FROM rdvs 
    JOIN coachs ON rdvs.coach_id = coachs.id
    JOIN users ON users.id = coachs.user_id
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
    <title>Mes rendez-vous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">📅 Mes rendez-vous</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'rdv_cancelled'): ?>
        <div class="alert alert-info">ℹ️ Rendez-vous annulé avec succès.</div>
    <?php endif; ?>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">Vous n’avez aucun rendez-vous pour le moment.</div>
    <?php else: ?>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rdv = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($rdv['coach_prenom'] . ' ' . $rdv['coach_nom']) ?></td>
                        <td><?= htmlspecialchars($rdv['date_rdv']) ?></td>
                        <td><?= htmlspecialchars(substr($rdv['heure_rdv'], 0, 5)) ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Confirmer l’annulation de ce rendez-vous ?')">
                                <input type="hidden" name="rdv_id" value="<?= (int)$rdv['id'] ?>">
                                <input type="hidden" name="coach_id" value="<?= (int)$rdv['coach_id'] ?>">
                                <button class="btn btn-danger btn-sm">Annuler</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
