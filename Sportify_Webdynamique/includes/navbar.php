<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$root = "/Sportify_Webdynamique";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $root ?>/index.php">Sportify</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <!-- MENU DÉROULANT COACHS -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Tout Parcourir
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=tous">Tous les coachs</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=musculation">Musculation</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=fitness">Fitness</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=biking">Biking</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=cardio-training">Cardio-training</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/coach_list.php?activite=cours%20collectifs">Cours collectifs</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= $root ?>/gym.php">🏟️ Salles Omnisports</a></li>
            <li><a class="dropdown-item" href="<?= $root ?>/competition.php">🏅 Sport de Compétition</a></li>
          </ul>
        </li>

        <!-- RECHERCHE -->
        <li class="nav-item">
          <a class="nav-link" href="<?= $root ?>/search.php">Recherche</a>
        </li>

        <!-- RENDEZ-VOUS -->
        <li class="nav-item">
          <a class="nav-link" href="<?= $root ?>/dashboard.php">Rendez-vous</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'client'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $root ?>/messagerie_client.php">Messagerie</a>
            </li>
          <?php elseif ($_SESSION['role'] === 'coach'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $root ?>/dashboard/messagerie_coach.php">Messagerie</a>
            </li>
          <?php endif; ?>

          <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $root ?>/dashboard/admin.php">Admin</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link disabled">Bienvenue, <?= htmlspecialchars($_SESSION['prenom']) ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $root ?>/logout.php">Déconnexion</a>
          </li>

        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $root ?>/login.php">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $root ?>/register.php">Inscription</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
