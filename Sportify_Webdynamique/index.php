<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
 <style>.
 .carousel-inner {
  max-width: 600px;     /* largeur maximale du carrousel */
  margin: 0 auto;       /* centré horizontalement */
}

.carousel-item img {
  height: 400px;        /* hauteur fixe */
  object-fit: cover;    /* rogner proprement */
  object-position: top; /* priorité au haut de l'image (visage visible) */
  width: 100%;          /* remplir la largeur du conteneur */
  border-radius: 10px;
}
</style>   

</head>
<body>

<!-- Header -->
<?php include 'includes/navbar.php'; ?>


<!-- Section de bienvenue -->
<div class="container mt-4">
    <div class="text-center">
        <h1>Bienvenue sur Sportify</h1>
        <p>La plateforme de consultation sportive pour la communauté Omnes Education.</p>
    </div>
</div>

<!-- Evénement de la semaine -->
<div class="container mt-5">
    <h3>🏅 Événement de la semaine</h3>
    <div class="alert alert-info">
        Ce samedi : Match amical entre Omnes Rugby et l’INSA Lyon ! Venez nombreux !
    </div>
</div>

<!-- Carrousel de coachs -->
<div id="carouselCoachs" class="carousel slide container mt-4" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="uploads/coach_alex.jpg" class="d-block w-100" alt="Alex Durand">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50">
        <h5>Alex Durand - Musculation</h5>
        <p>Expert en remise en forme depuis 10 ans.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="uploads/coach_camille_index.jpg" class="d-block w-100" alt="Coach 2">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50">
        <h5>Camille Robin - Basketball (sports collectifs)</h5>
        <p>Championne universitaire de basketball.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselCoachs" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselCoachs" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Footer avec carte -->
<footer class="bg-dark text-white mt-5 p-4">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <p>Contact : sportify@omnes.com</p>
        <p>Téléphone : +33 1 23 45 67 89</p>
        <p>Adresse : 10 Rue Sextius Michel, 75015 Paris</p>
      </div>
      <div class="col-md-6">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.337537422541!2d2.283069615674603!3d48.84933417928743!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67021963d0951%3A0xf85cc53e28a5180a!2s10%20Rue%20Sextius%20Michel%2C%2075015%20Paris!5e0!3m2!1sfr!2sfr!4v1620329878835!5m2!1sfr!2sfr"
          width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
