<?php
session_start();
include 'includes/db.php';

$query = "";
$resultats = [];

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['q'])) {
    $query = trim($_GET['q']);
    
    $stmt = $conn->prepare("
        SELECT * FROM users 
        WHERE role = 'coach' 
        AND (nom LIKE CONCAT('%', ?, '%') OR prenom LIKE CONCAT('%', ?, '%'))
    ");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $res = $stmt->get_result();
    $resultats = $res->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Sportify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">🔎 Recherche de coachs</h2>

    <form method="GET" class="mb-4 d-flex gap-2">
        <input type="text" name="q" class="form-control" placeholder="Nom ou prénom du coach..." value="<?= htmlspecialchars($query) ?>" required>
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    <?php if (!empty($resultats)): ?>
        <div class="row">
            <?php foreach ($resultats as $coach): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($coach['prenom'] . ' ' . $coach['nom']) ?></h5>
                            <a href="coach_profile.php?id=<?= $coach['id'] ?>" class="btn btn-primary btn-sm">Voir profil</a>
                            <a href="appointments/book.php?coach_id=<?= $coach['id'] ?>" class="btn btn-success btn-sm">Prendre RDV</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($query !== ""): ?>
        <div class="alert alert-warning">
            Aucun coach trouvé pour « <?= htmlspecialchars($query) ?> ».
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html
