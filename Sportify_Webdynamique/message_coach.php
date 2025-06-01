<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Envoi du message
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $coach_id = $_POST['coach_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $client_id, $coach_id, $message);
        if ($stmt->execute()) {
            $success = "Message envoyé ✅";
        } else {
            $error = "Erreur lors de l'envoi : " . $stmt->error;
        }
    } else {
        $error = "Le message ne peut pas être vide.";
    }
}

// Liste des coachs avec qui le client a eu un RDV
$coachs = $conn->query("
    SELECT DISTINCT u.id, u.prenom, u.nom 
    FROM rdvs r 
    JOIN users u ON u.id = r.coach_id 
    WHERE r.client_id = $client_id
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contacter un Coach</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5 col-md-6">
  <h2 class="mb-4">📨 Contacter un Coach</h2>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="coach" class="form-label">Coach</label>
      <select name="coach_id" id="coach" class="form-select" required>
        <?php while ($coach = $coachs->fetch_assoc()): ?>
          <option value="<?= $coach['id'] ?>"><?= htmlspecialchars($coach['prenom'] . " " . $coach['nom']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea name="message" id="message" class="form-control" rows="5" placeholder="Votre message..." required></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">Envoyer</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
