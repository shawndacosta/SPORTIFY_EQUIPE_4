<?php
session_start();
include 'includes/db.php';

$id = $_GET['id'] ?? 0;

// Sécurité : s'assurer que l'ID est un entier
$id = (int)$id;

$stmt = $conn->prepare("SELECT coachs.*, users.nom, users.prenom, users.email 
                        FROM coachs 
                        JOIN users ON coachs.user_id = users.id 
                        WHERE coachs.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$coach = $result->fetch_assoc();

if (!$coach) {
    echo "Coach introuvable.";
    exit();
}

// Fonction sécurisée pour l'affichage
function safe($value, $default = 'Non renseigné') {
    return $value !== null ? htmlspecialchars($value) : $default;
}

// Utiliser une image par défaut si pas de photo
$photo = !empty($coach['photo']) ? "uploads/" . htmlspecialchars($coach['photo']) : "uploads/default.jpg";

// Vérifie la disponibilité d'un CV
$cvDisponible = !empty($coach['cv_path']) && file_exists($coach['cv_path']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil du coach</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="card mb-4 bg-dark text-white border border-success">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?= $photo ?>" class="img-fluid rounded-start" alt="Photo du coach">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3><?= safe($coach['prenom'] . " " . $coach['nom']) ?></h3>
                    <p><strong>Spécialité :</strong> <?= safe($coach['specialite']) ?></p>
                    <p><strong>Bureau :</strong> <?= safe($coach['bureau']) ?></p>
                    <p><strong>Email :</strong> <?= safe($coach['email']) ?></p>

                    <a href="appointments/book.php?coach_id=<?= $coach['user_id'] ?>" class="btn btn-success mb-2">📅 Prendre un RDV</a>
                    <a href="messagerie.php?avec=<?= $coach['user_id'] ?>" class="btn btn-outline-primary mb-2">📩 Communiquer</a>

                    <?php if ($cvDisponible): ?>
                        <a href="<?= htmlspecialchars($coach['cv_path']) ?>" target="_blank" class="btn btn-outline-light mt-2">📄 Voir le CV</a>
                    <?php else: ?>
                        <p class="mt-2 text-muted">CV non disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
