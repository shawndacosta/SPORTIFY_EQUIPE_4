<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom      = trim($_POST['nom'] ?? '');
    $prenom   = trim($_POST['prenom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $activite = $_POST['activite'] ?? '';
    $role     = "coach";

    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($activite)) {
        $error = "❗ Tous les champs sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❗ Adresse email invalide.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Vérifie si l'email existe déjà
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "❗ Cet email est déjà utilisé.";
        } else {
            // Insertion dans users
            $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nom, $prenom, $email, $password_hashed, $role);

            if ($stmt->execute()) {
                $new_user_id = $stmt->insert_id;

                // Insertion dans coachs
                $stmt2 = $conn->prepare("INSERT INTO coachs (user_id, activite) VALUES (?, ?)");
                $stmt2->bind_param("is", $new_user_id, $activite);

                if ($stmt2->execute()) {
                    $stmt2->close();
                    $stmt->close();
                    $check->close();
                    header("Location: admin.php?success=coach_created");
                    exit();
                } else {
                    $error = "❌ Erreur lors de l'ajout du coach dans la table 'coachs' : " . $stmt2->error;
                }

                $stmt2->close();
            } else {
                $error = "❌ Erreur lors de la création de l'utilisateur : " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un coach</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 col-md-6">
    <a href="admin.php" class="btn btn-outline-secondary mb-3">⬅ Retour au tableau de bord</a>
    <h2 class="mb-4">👤 Ajouter un coach</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'coach_created'): ?>
        <div class="alert alert-success">✅ Coach ajouté avec succès.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input class="form-control mb-2" type="text" name="nom" placeholder="Nom" required>
        <input class="form-control mb-2" type="text" name="prenom" placeholder="Prénom" required>
        <input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-2" type="password" name="password" placeholder="Mot de passe" required>

        <div class="mb-3">
            <label for="activite" class="form-label">Activité du coach</label>
            <select name="activite" id="activite" class="form-select" required>
                <option value="">-- Choisir une activité --</option>
                <option value="musculation">Musculation</option>
                <option value="fitness">Fitness</option>
                <option value="biking">Biking</option>
                <option value="cardio-training">Cardio-training</option>
                <option value="cours collectifs">Cours collectifs</option>
            </select>
        </div>

        <button class="btn btn-primary w-100" type="submit">Créer le coach</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
