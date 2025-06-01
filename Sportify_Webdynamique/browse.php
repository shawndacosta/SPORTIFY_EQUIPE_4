<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Tout Parcourir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'includes/db.php'; ?>

<?php include 'includes/navbar.php'; ?>


<div class="container mt-5">
    <h2 class="text-center mb-4">Parcourez les Services Sportifs</h2>

    <div class="row g-4">
        <!-- Activités sportives -->
        <div class="col-md-4">
            <div class="card h-100">
                <img src="uploads/fitness.jpg" class="card-img-top" alt="Activités sportives">
                <div class="card-body">
                    <h5 class="card-title">Activités sportives</h5>
                    <p class="card-text">Musculation, Fitness, Biking, Cardio-Training, Cours Collectifs.</p>
                    <a href="activities.php" class="btn btn-primary">Explorer</a>
                </div>
            </div>
        </div>

        <!-- Sports de compétition -->
        <div class="col-md-4">
            <div class="card h-100">
                <img src="uploads/competition.jpg" class="card-img-top" alt="Sports compétitifs">
                <div class="card-body">
                    <h5 class="card-title">Sports de compétition</h5>
                    <p class="card-text">Basketball, Football, Rugby, Tennis, Natation, Plongeon.</p>
                    <a href="competition.php" class="btn btn-primary">Découvrir</a>
                </div>
            </div>
        </div>

        <!-- Salle de sport Omnes -->
        <div class="col-md-4">
            <div class="card h-100">
                <img src="uploads/gym.jpg" class="card-img-top" alt="Salle de sport">
                <div class="card-body">
                    <h5 class="card-title">Salle de sport Omnes</h5>
                    <p class="card-text">Consultez les horaires, règles, services et prenez un rendez-vous.</p>
                    <a href="gym.php" class="btn btn-primary">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
