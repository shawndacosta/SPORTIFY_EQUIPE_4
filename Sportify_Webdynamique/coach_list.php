<?php
session_start();
include 'includes/db.php';

$activite = $_GET['activite'] ?? null;

if ($activite && strtolower($activite) !== 'tous') {
    $stmt = $conn->prepare("SELECT coachs.id AS coach_id, users.nom, users.prenom, coachs.photo, coachs.activite
        FROM coachs 
        JOIN users ON coachs.user_id = users.id 
        WHERE TRIM(LOWER(coachs.activite)) = TRIM(LOWER(?))");
    $stmt->bind_param("s", $activite);
} else {
    // Aucun filtre ou 'tous' : affiche tous les coachs
    $stmt = $conn->prepare("SELECT coachs.id AS coach_id, users.nom, users.prenom, coachs.photo, coachs.activite
        FROM coachs 
        JOIN users ON coachs.user_id = users.id");
}


$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Coachs - <?= $activite ? htmlspecialchars($activite) : 'Tous' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <a href="browse.php" class="btn btn-outline-secondary mb-3">⬅ Retour</a>

    <h2 class="mb-4 text-capitalize">
        <?= $activite ? "Coachs pour l'activité : " . htmlspecialchars($activite) : "Tous les coachs disponibles" ?>
    </h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-warning">
            Aucun coach trouvé<?= $activite ? " pour l'activité : " . htmlspecialchars($activite) : "" ?>.
        </div>
        <p class="text-muted">Activités disponibles en base :</p>
        <ul>
            <?php
            $check = $conn->query("SELECT DISTINCT activite FROM coachs");
            while ($row = $check->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['activite']) . "</li>";
            }
            ?>
        </ul>
    <?php endif; ?>

    <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $photo = !empty($row['photo']) ? htmlspecialchars($row['photo']) : 'default.jpg'; ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="uploads/<?= $photo ?>" class="card-img-top" alt="Coach" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></h5>
                        <p class="card-text">Spécialité : <?= htmlspecialchars($row['activite']) ?></p>
                        <a href="coach_profile.php?id=<?= $row['coach_id'] ?>" class="btn btn-success">Voir profil</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
