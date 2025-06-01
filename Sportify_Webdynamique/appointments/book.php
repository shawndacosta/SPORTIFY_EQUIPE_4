<?php
session_start();
include '../includes/db.php';

// Vérifie si un coach est sélectionné via l'URL ou session
$coach_user_id = $_GET['coach_id'] ?? ($_SESSION['selected_coach'] ?? 0);
if (!$coach_user_id) {
    echo "<div class='alert alert-danger text-center mt-5'>❌ Aucun coach sélectionné.</div>";
    exit();
}

// Récupère l'ID interne du coach (coachs.id) à partir du user_id
$stmt = $conn->prepare("SELECT id FROM coachs WHERE user_id = ?");
$stmt->bind_param("i", $coach_user_id);
$stmt->execute();
$res = $stmt->get_result();
$coach = $res->fetch_assoc();

if (!$coach) {
    echo "<div class='alert alert-danger text-center mt-5'>❌ Coach introuvable.</div>";
    exit();
}
$coach_id = $coach['id'];
$_SESSION['selected_coach'] = $coach_user_id;

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $dispo_id = $_POST['dispo_id'];

    // Récupération du créneau sélectionné
    $stmt_dispo = $conn->prepare("SELECT jour, heure FROM disponibilites WHERE id = ?");
    $stmt_dispo->bind_param("i", $dispo_id);
    $stmt_dispo->execute();
    $res_dispo = $stmt_dispo->get_result()->fetch_assoc();
    $stmt_dispo->close();

    if ($res_dispo) {
        // Marquer le créneau comme réservé
        $stmt = $conn->prepare("UPDATE disponibilites SET disponible = 0 WHERE id = ?");
        $stmt->bind_param("i", $dispo_id);
        $stmt->execute();

        // Calcule la prochaine date correspondante
        $jour = strtolower($res_dispo['jour']);
        $heure = $res_dispo['heure'];

        $jourMap = [
            'lundi' => 1, 'mardi' => 2, 'mercredi' => 3,
            'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6, 'dimanche' => 7
        ];
        $today = new DateTime();
        $targetDay = $jourMap[$jour] ?? 1;
        $currentDay = (int)$today->format('N');
        $daysUntil = ($targetDay - $currentDay + 7) % 7;
        if ($daysUntil === 0) $daysUntil = 7;

        $date_rdv = $today->modify("+$daysUntil days")->format('Y-m-d');

        // Enregistrement du rendez-vous
        $stmt2 = $conn->prepare("INSERT INTO rdvs (client_id, coach_id, date_rdv, heure_rdv) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iiss", $_SESSION['user_id'], $coach_id, $date_rdv, $heure);
        $stmt2->execute();
        $stmt2->close();

        header("Location: ../dashboard/client.php?success=rdv_booked");
        exit();
    }
}

// Récupération des créneaux disponibles
$stmt = $conn->prepare("
    SELECT * FROM disponibilites 
    WHERE coach_id = ? 
    ORDER BY FIELD(jour, 'lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'), heure
");
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$res = $stmt->get_result();

$slots = [];
while ($row = $res->fetch_assoc()) {
    $slots[$row['jour']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Prendre un RDV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <a href="javascript:history.back()" class="btn btn-outline-secondary mb-4">⬅ Retour</a>
    <h2 class="mb-4">📆 Choisissez un créneau disponible</h2>

    <?php if (empty($slots)): ?>
        <div class="alert alert-warning">Aucun créneau disponible pour ce coach actuellement.</div>
    <?php endif; ?>

    <div class="row text-center">
        <?php foreach ($slots as $jour => $dispos): ?>
            <div class="col-md-2 mb-4">
                <h5 class="text-success"><?= ucfirst($jour) ?></h5>
                <?php foreach ($dispos as $slot): ?>
                    <?php if ((int)$slot['disponible'] === 1): ?>
                        <form method="POST" class="mb-2">
                            <input type="hidden" name="dispo_id" value="<?= $slot['id'] ?>">
                            <button type="submit" class="btn btn-success btn-sm"><?= substr($slot['heure'], 0, 5) ?></button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm mb-2" disabled><?= substr($slot['heure'], 0, 5) ?></button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
