<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$prenom = $_SESSION['prenom'];
$other_id = $_GET['avec'] ?? null;

if (!$other_id || $other_id == $user_id) {
    die("Erreur : identifiant d'interlocuteur invalide.");
}

// Récupération de l’interlocuteur
$other_prenom = null;
$stmt = $conn->prepare("SELECT prenom FROM users WHERE id = ?");
$stmt->bind_param("i", $other_id);
$stmt->execute();
$stmt->bind_result($result_prenom);
if ($stmt->fetch()) {
    $other_prenom = $result_prenom;
}
$stmt->close();

// Envoi de message
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['message'])) {
    $msg = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, message, date_envoi) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $other_id, $msg);
    $stmt->execute();
    $stmt->close();
}

// Historique
$stmt = $conn->prepare("
    SELECT m.*, u.prenom 
    FROM messages m
    JOIN users u ON m.expediteur_id = u.id
    WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
       OR (m.expediteur_id = ? AND m.destinataire_id = ?)
    ORDER BY m.date_envoi ASC
");
$stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie avec <?= htmlspecialchars($other_prenom ?? 'Interlocuteur inconnu') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary mb-3">⬅ Retour</a>

    <h3 class="mb-3">💬 Conversation avec <?= htmlspecialchars($other_prenom ?? 'Inconnu') ?></h3>

    <div class="border p-3 mb-4" style="height: 300px; overflow-y: scroll; background: #1e1e1e; color: white;">
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="mb-2">
                <strong><?= htmlspecialchars($msg['prenom']) ?> :</strong><br>
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <div><small class="text-muted">(<?= $msg['date_envoi'] ?>)</small></div>
            </div>
            <hr>
        <?php endwhile; ?>
    </div>

    <form method="POST">
        <textarea name="message" class="form-control mb-2" placeholder="Votre message..." required></textarea>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
