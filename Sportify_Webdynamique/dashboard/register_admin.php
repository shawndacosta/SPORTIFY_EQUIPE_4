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
    $role     = "admin";

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "❗ Tous les champs sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❗ Adresse email invalide.";
    } elseif (strlen($password) < 6) {
        $error = "❗ Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "❗ Cet email est déjà utilisé.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nom, $prenom, $email, $password_hashed, $role);

            if ($stmt->execute()) {
                $stmt->close();
                $check->close();
                header("Location: admin.php?success=admin_created");
                exit();
            } else {
                $error = "❌ Erreur lors de la création : " . $stmt->error;
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
  <title>Ajouter un admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 col-md-6">
    <a href="admin.php" class="btn btn-outline-secondary mb-3">⬅ Retour au tableau de bord</a>

    <h2 class="mb-4">👤 Ajouter un administrateur</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input class="form-control mb-2" type="text" name="nom" placeholder="Nom" required>
        <input class="form-control mb-2" type="text" name="prenom" placeholder="Prénom" required>
        <input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="Mot de passe" required>
        <button class="btn btn-danger w-100" type="submit">Créer l'admin</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
