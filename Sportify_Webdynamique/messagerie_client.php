<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT DISTINCT coachs.user_id AS coach_id, users.prenom, users.nom 
    FROM rdvs 
    JOIN coachs ON rdvs.coach_id = coachs.id
    JOIN users ON users.id = coachs.user_id
    WHERE rdvs.client_id = ?
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie - Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <a href="dashboard.php" class="btn btn-outline-secondary mb-3">⬅ Retour</a>
    <h3>📨 Messagerie avec vos coachs</h3>

    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?>
                    <a href="messagerie.php?avec=<?= $row['coach_id'] ?>" class="btn btn-primary btn-sm">Discuter</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info mt-3">Aucun coach trouvé avec qui vous avez un rendez-vous.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
