<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ajout d’une disponibilité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coach_id'], $_POST['jour'], $_POST['heure'])) {
    $coach_id = $_POST['coach_id'];
    $jour = strtolower($_POST['jour']);
    $heure = $_POST['heure'];

    // Insertion ou mise à jour
    $stmt = $conn->prepare("REPLACE INTO disponibilites (coach_id, jour, heure, disponible) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("iss", $coach_id, $jour, $heure);
    $stmt->execute();
}

// Suppression d'une dispo
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM disponibilites WHERE id = $id");
}

// Récupération des coachs
$coachs = $conn->query("
    SELECT users.id, users.prenom, users.nom 
    FROM users 
    JOIN coachs ON users.id = coachs.user_id 
    ORDER BY prenom
");

// Si coach sélectionné, récupérer ses dispos
$dispos = [];
if (isset($_GET['coach_id'])) {
    $coach_id = intval($_GET['coach_id']);
    $stmt = $conn->prepare("SELECT * FROM disponibilites WHERE coach_id = ?");
    $stmt->bind_param("i", $coach_id);
    $stmt->execute();
    $dispos = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dispos Coachs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>📅 Gérer les disponibilités des coachs</h2>

    <!-- Formulaire de sélection -->
    <form method="GET" class="row g-2 mt-3 mb-4">
        <div class="col-md-6">
            <select name="coach_id" class="form-select" required onchange="this.form.submit()">
                <option value="">-- Sélectionner un coach --</option>
                <?php while ($c = $coachs->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>" <?= (isset($coach_id) && $coach_id == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <?php if (isset($coach_id)): ?>
        <!-- Formulaire d'ajout -->
        <form method="POST" class="row g-2 align-items-end mb-3">
            <input type="hidden" name="coach_id" value="<?= $coach_id ?>">
            <div class="col-md-4">
                <label>Jour :</label>
                <select name="jour" class="form-select" required>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Heure :</label>
                <input type="time" name="heure" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </div>
        </form>

        <!-- Tableau des créneaux -->
        <h5>Créneaux de disponibilité</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Jour</th><th>Heure</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($dispos as $d): ?>
                    <tr>
                        <td><?= ucfirst($d['jour']) ?></td>
                        <td><?= substr($d['heure'], 0, 5) ?></td>
                        <td>
                            <a href="?coach_id=<?= $coach_id ?>&delete=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer ce créneau ?')">❌ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($dispos)): ?>
                    <tr><td colspan="3">Aucune disponibilité pour ce coach.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
