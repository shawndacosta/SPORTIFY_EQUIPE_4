<?php
session_start();
include 'includes/db.php';

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, mot_de_passe, role, prenom FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash, $role, $prenom);
        $stmt->fetch();

        if (password_verify($mot_de_passe, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['prenom'] = $prenom;

            if ($role === 'admin') {
                header("Location: /Sportify_Webdynamique/dashboard/admin.php");
            } elseif ($role === 'coach') {
                header("Location: /Sportify_Webdynamique/dashboard/coach.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Aucun utilisateur trouvé avec cet email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5 col-md-6">
    <h2 class="mb-4">🔐 Connexion</h2>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="exemple@mail.com" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
</form>

    <p class="mt-3">Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
