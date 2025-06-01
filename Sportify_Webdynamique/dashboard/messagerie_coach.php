<?php
session_start();
include '../includes/db.php';

// Vérifie que l'utilisateur est connecté et est coach
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach') {
    header("Location: ../login.php");
    exit();
}

$coach_id = $_SESSION['user_id'];

// Aucune conversation sélectionnée : afficher la liste des clients
if (!isset($_GET['avec'])) {
    $stmt = $conn->prepare("
        SELECT DISTINCT users.id, users.prenom, users.nom
        FROM rdvs
        JOIN users ON rdvs.client_id = users.id
        WHERE rdvs.coach_id = ?
    ");
    $stmt->bind_param("i", $coach_id);
    $stmt->execute();
    $clients = $stmt->get_result();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Messagerie - Coach</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h3>💬 Choisissez un client avec qui discuter</h3>
        <?php if ($clients->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($client = $clients->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                        <a href="messagerie_coach.php?avec=<?= $client['id'] ?>" class="btn btn-primary btn-sm">Discuter</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info mt-3">Vous n'avez aucun client avec rendez-vous.</div>
        <?php endif; ?>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// ID client spécifié : traitement de la conversation
$client_id = (int)$_GET['avec'];

if ($client_id === $coach_id || $client_id <= 0) {
    die("Erreur : identifiant d'interlocuteur invalide.");
}

// Vérifie que ce client existe bien
$stmt = $conn->prepare("SELECT prenom FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$stmt->bind_result($client_prenom);
if (!$stmt->fetch()) {
    die("Erreur : client introuvable.");
}
$stmt->close();

// Envoi de message
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['message'])) {
    $msg = trim($_POST['message']);
    if ($msg !== "") {
        $stmt = $conn->prepare("
            INSERT INTO messages (expediteur_id, destinataire_id, message, date_envoi)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $coach_id, $client_id, $msg);
        $stmt->execute();
        $stmt->close();
    }
}

// Récupération de l'historique
$stmt = $conn->prepare("
    SELECT m.*, u.prenom
    FROM messages m
    JOIN users u ON m.expediteur_id = u.id
    WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
       OR (m.expediteur_id = ? AND m.destinataire_id = ?)
    ORDER BY m.date_envoi ASC
");
$stmt->bind_param("iiii", $coach_id, $client_id, $client_id, $coach_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie avec <?= htmlspecialchars($client_prenom) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <a href="messagerie_coach.php" class="btn btn-outline-secondary mb-3">⬅ Retour à la liste des clients</a>
    <h3>💬 Conversation avec <?= htmlspecialchars($client_prenom) ?></h3>

    <div class="border p-3 mb-4" style="height: 300px; overflow-y: scroll; background: #f9f9f9;">
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div>
                <strong><?= htmlspecialchars($msg['prenom']) ?> :</strong>
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <small class="text-muted">(<?= $msg['date_envoi'] ?>)</small>
            </div>
            <hr>
        <?php endwhile; ?>
    </div>

    <form method="POST">
        <textarea name="message" class="form-control mb-2" placeholder="Votre message..." required></textarea>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
</body>
</html>
