<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ajout ou mise à jour de disponibilité
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $coach_id = intval($_POST['coach_id']);
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $stmt = $conn->prepare("REPLACE INTO disponibilites (coach_id, jour, heure, disponible) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $coach_id, $jour, $heure, $disponible);
    $stmt->execute();
    $stmt->close();
}

// Suppression
if (isset($_GET['delete'])) {
    $dispo_id = intval($_GET['delete']);
    $conn->query("DELETE FROM disponibilites WHERE id = $dispo_id");
}

// Liste des coachs
$coachs = $conn->query("SELECT id, prenom, nom FROM users WHERE role = 'coach'");

// Coach sélectionné ?
$selected_id = $_GET['coach_id'] ?? null;
$dispos = [];
if ($selected_id) {
    $stmt = $conn->prepare("SELECT * FROM disponibilites WHERE coach_id = ? ORDER BY jour, heure");
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $dispos = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les disponibilités</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
    <a href="admin.php" class="btn btn-outline-secondary mb-3">⬅ Retour</a>
    <h3>🗓️ Gestion des disponibilités</h3>

    <form method="POST" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label>Coach :</label>
            <select name="coach_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php while ($coach = $coachs->fetch_assoc()): ?>
                    <option value="<?= $coach['id'] ?>" <?= ($selected_id == $coach['id'] ? 'selected' : '') ?>>
                        <?= htmlspecialchars($coach['prenom'] . ' ' . $coach['nom']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label>Jour :</label>
            <select name="jour" class="form-select" required>
                <?php foreach (["monday"=>"Lundi", "tuesday"=>"Mardi", "wednesday"=>"Mercredi", "thursday"=>"Jeudi", "friday"=>"Vendredi", "saturday"=>"Samedi", "sunday"=>"Dimanche"] as $key => $label): ?>
                    <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label>Heure :</label>
            <input type="time" name="heure" class="form-control" required>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input" name="disponible" value="1" checked>
                <label class="form-check-label">Disponible</label>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success">✅ Ajouter / Modifier</button>
        </div>
    </form>

    <?php if ($selected_id && $dispos->num_rows > 0): ?>
        <h5>📋 Créneaux existants</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Jour</th><th>Heure</th><th>Disponible</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = $dispos->fetch_assoc()): ?>
                <tr>
                    <td><?= ucfirst($row['jour']) ?></td>
                    <td><?= substr($row['heure'], 0, 5) ?></td>
                    <td><?= $row['disponible'] ? '✅' : '❌' ?></td>
                    <td><a href="?coach_id=<?= $selected_id ?>&delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Supprimer</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($selected_id): ?>
        <div class="alert alert-warning">Aucune disponibilité trouvée pour ce coach.</div>
    <?php endif; ?>
</div>
</body>
</html>
