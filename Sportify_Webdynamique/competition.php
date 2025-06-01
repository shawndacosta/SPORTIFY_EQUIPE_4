<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Sport de Compétition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

    <a href="index.php" class="btn btn-outline-secondary mb-4">⬅ Retour à l'accueil</a>

    <h1 class="mb-4">🏅 Sport de Compétition</h1>

    <p>Sportify accompagne les sportifs en quête de performance grâce à des coachs spécialisés dans l’encadrement de haut niveau.</p>

    <h2 class="mt-5">⚡ Objectifs visés</h2>
    <ul>
        <li>Optimisation des performances</li>
        <li>Préparation aux compétitions officielles</li>
        <li>Suivi personnalisé et rigoureux</li>
        <li>Programmes de musculation et de diététique avancés</li>
    </ul>

    <h2 class="mt-5">👤 Coachs spécialisés</h2>
    <p>Des coachs qualifiés vous accompagnent selon votre discipline : musculation, endurance, sports collectifs, etc.</p>

    <p class="mt-4">Consultez les profils disponibles via <strong>Tout Parcourir</strong> ou utilisez la <strong>Recherche</strong> pour trouver un coach adapté à vos besoins.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
