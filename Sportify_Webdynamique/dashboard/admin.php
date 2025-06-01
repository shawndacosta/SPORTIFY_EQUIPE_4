<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$users = $conn->query("SELECT id, nom, prenom, email, role FROM users ORDER BY role, prenom");

$rdvs = $conn->query("
    SELECT r.date_rdv, r.heure_rdv, u1.prenom AS client, u2.prenom AS coach
    FROM rdvs r
    JOIN users u1 ON r.client_id = u1.id
    JOIN users u2 ON r.coach_id = u2.id
    ORDER BY r.date_rdv, r.heure_rdv
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tableau de bord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
  <div class="d-flex justify-content-end mb-3">
    <a href="../logout.php" class="btn btn-outline-danger">🔓 Se déconnecter</a>
  </div>

  <h2>👑 Tableau de bord Admin</h2>

  <!-- BOUTONS DE CRÉATION -->
<div class="mb-4">
  <a href="register_coach.php" class="btn btn-success mb-2">+ Ajouter un coach</a>
  <a href="register_admin.php" class="btn btn-danger mb-2">+ Ajouter un admin</a>
  <a href="admin_dispos.php" class="btn btn-warning mb-2">🕒 Gérer les créneaux coachs</a>
</div>

  <h4>👥 Tous les utilisateurs</h4>
  <table class="table table-bordered mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['nom']) ?></td>
          <td><?= htmlspecialchars($user['prenom']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= $user['role'] ?></td>
          <td>
            <?php if ($_SESSION['user_id'] != $user['id']): ?>
              <form method="POST" action="delete_user.php" onsubmit="return confirm('Confirmer la suppression ?');">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <button class="btn btn-sm btn-danger">Supprimer</button>
              </form>
            <?php else: ?>
              <span class="text-muted">Moi</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h4 class="mt-5">📅 Tous les rendez-vous</h4>
  <table class="table table-striped mt-3">
    <thead class="table-secondary">
      <tr>
        <th>Client</th>
        <th>Coach</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($rdv = $rdvs->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($rdv['client']) ?></td>
          <td><?= htmlspecialchars($rdv['coach']) ?></td>
          <td><?= $rdv['date_rdv'] ?></td>
          <td><?= substr($rdv['heure_rdv'], 0, 5) ?></td>
          <td>
            <form method="POST" action="delete_rdv.php" onsubmit="return confirm('Supprimer ce rendez-vous ?');">
              <input type="hidden" name="date" value="<?= $rdv['date_rdv'] ?>">
              <input type="hidden" name="heure" value="<?= $rdv['heure_rdv'] ?>">
              <input type="hidden" name="client" value="<?= htmlspecialchars($rdv['client']) ?>">
              <input type="hidden" name="coach" value="<?= htmlspecialchars($rdv['coach']) ?>">
              <button class="btn btn-sm btn-outline-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
