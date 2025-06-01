<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Salles Omnisports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

    <a href="index.php" class="btn btn-outline-secondary mb-4">⬅ Retour à l'accueil</a>

    <h1 class="mb-4">🏟️ Salles Omnisports</h1>

    <p>Les salles omnisports proposées par Sportify sont des lieux polyvalents permettant la pratique de nombreux sports collectifs et individuels dans des installations de qualité.</p>

    <h2 class="mt-5">✔️ Activités disponibles</h2>
    <ul>
        <li>Basket-ball</li>
        <li>Volley-ball</li>
        <li>Badminton</li>
        <li>Handball</li>
        <li>Gymnastique artistique</li>
    </ul>

    <h2 class="mt-5">👥 Encadrement & Services</h2>
    <ul>
        <li>Encadrement par des coachs spécialisés selon l'activité</li>
        <li>Réservation de créneaux via la plateforme</li>
        <li>Accès à des vestiaires, douches et équipements sportifs</li>
    </ul>

    <p class="mt-4">Pour réserver une séance ou consulter les coachs disponibles, utilisez l'onglet <strong>Tout Parcourir</strong> ou la section <strong>Rendez-vous</strong>.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
