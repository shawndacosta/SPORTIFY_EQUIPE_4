<?php
session_start();
include '../includes/db.php';

// Vérification que l'utilisateur est bien un coach
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach') {
    header("Location: ../login.php");
    exit();
}

$coach_id = $_SESSION['user_id'];

// Ajout d’un créneau
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['jour'], $_POST['heure'])) {
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];

    $stmt_add = $conn->prepare("INSERT INTO disponibilites (coach_id, jour, heure, disponible) VALUES (?, ?, ?, 1)");
    $stmt_add->bind_param("iss", $coach_id, $jour, $heure);
    $stmt_add->execute();
}

// Suppression d’un créneau
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt_del = $conn->prepare("DELETE FROM disponibilites WHERE id = ? AND coach_id = ?");
    $stmt_del->bind_param("ii", $delete_id, $coach_id);
    $stmt_del->execute();
}

// Récupération des RDV
$stmt = $conn->prepare("
    SELECT rdvs.date_rdv, rdvs.heure_rdv, users.prenom
    FROM rdvs
    JOIN users ON rdvs.client_id = users.id
    WHERE rdvs.coach_id = ?
    ORDER BY rdvs.date_rdv, rdvs.heure_rdv
");
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$result = $stmt->get_result();

// Récupération des créneaux du coach
$stmt_dispos = $conn->prepare("SELECT * FROM disponibilites WHERE coach_id = ? ORDER BY FIELD(jour, 'lundi','mardi','mercredi','jeudi','vendredi','samedi'), heure");
$stmt_dispos->bind_param("i", $coach_id);
$stmt_dispos->execute();
$res_dispos = $stmt_dispos->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Coach - Mes Rendez-vous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mt-3 ms-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">⬅ Retour</a>
        <a href="messagerie_coach.php" class="btn btn-primary mt-3 ms-3">📨 Messagerie</a>
    </div>

    <h2>📋 Mes rendez-vous</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Heure</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['prenom']) ?></td>
                        <td><?= htmlspecialchars($row['date_rdv']) ?></td>
                        <td><?= substr($row['heure_rdv'], 0, 5) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aucun rendez-vous prévu.</div>
    <?php endif; ?>
</div>

<hr class="my-5">

<div class="container">
    <h2>🕒 Mes disponibilités</h2>

    <!-- Formulaire d'ajout -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="jour" class="form-label">Jour</label>
            <select name="jour" id="jour" class="form-select" required>
                <option value="">-- Choisir un jour --</option>
                <option value="lundi">Lundi</option>
                <option value="mardi">Mardi</option>
                <option value="mercredi">Mercredi</option>
                <option value="jeudi">Jeudi</option>
                <option value="vendredi">Vendredi</option>
                <option value="samedi">Samedi</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="heure" class="form-label">Heure</label>
            <input type="time" name="heure" id="heure" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label invisible">Ajouter</label>
            <button type="submit" class="btn btn-success w-100">➕ Ajouter</button>
        </div>
    </form>

    <!-- Liste des créneaux -->
    <?php if ($res_dispos->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Jour</th>
                    <th>Heure</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($d = $res_dispos->fetch_assoc()): ?>
                    <tr>
                        <td><?= ucfirst($d['jour']) ?></td>
                        <td><?= substr($d['heure'], 0, 5) ?></td>
                        <td>
                            <a href="?delete_id=<?= $d['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce créneau ?')">🗑 Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Aucune disponibilité définie.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
