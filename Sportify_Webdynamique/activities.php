<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <title>Sportify - Activités sportives</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include 'includes/db.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Activités sportives disponibles</h2>

    <div class="row g-4">
        <?php
        $activities = [
            "musculation" => "muscu.jpg",
            "fitness" => "fitness.jpg",
            "biking" => "biking.jpg",
            "cardio-training" => "cardio.jpg",
            "cours collectifs" => "cours.jpg"
        ];

        foreach ($activities as $name => $image) {
            $imagePath = "uploads/" . $image;
            $imageToDisplay = file_exists($imagePath) ? $imagePath : "uploads/default.jpg";

            echo '
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="' . htmlspecialchars($imageToDisplay) . '" class="card-img-top" alt="' . htmlspecialchars(ucfirst($name)) . '">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">' . htmlspecialchars($name) . '</h5>
                        <a href="coach_list.php?activite=' . urlencode($name) . '" class="btn btn-primary">Voir les coachs</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
