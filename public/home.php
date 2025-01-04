<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel ='stylesheet' href="./scss/main.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
<?php
// Vérifier si le message de session est défini
if (isset($_SESSION['message'])) {
    // Afficher le message
    echo '<div class="message-flash">' . $_SESSION['message'] . '</div>';

    // Effacer le message après l'avoir affiché (pour éviter qu'il s'affiche à nouveau)
    unset($_SESSION['message']);
}
?>
<header>
  <nav class="navbar navbar-expand-lg" style="background-color: #A5D6A7;">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img src="/Ressources/EcoRide-removebg-preview.png" alt="Logo" style="height: 100px;">
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
          <?php if (isset($_SESSION['utilisateur_id'])): ?> 
          <?php if (isset($_SESSION['role_meta']) && ($_SESSION['role_meta'] === 'employe' || $_SESSION['role_meta'] === 'administrateur')): ?>
    <li class="nav-item">
      <a class="nav-link" href="deconnexion.php">Déconnexion</a>
    </li>
  <?php else: ?>
    <li class="nav-item">
      <a class="nav-link" href="/">Accueil</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/contact">Aide</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/recherche">Rechercher</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/profil">Mon Profil</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="deconnexion.php">Déconnexion</a>
    </li>
  <?php endif; ?>
<?php else: ?>
  <li class="nav-item">
    <a class="nav-link" href="/">Accueil</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/contact">Aide</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/recherche">Rechercher</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/signup">Connexion</a>
  </li>
<?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>
</header>

<main id="main-page">
  <?php echo $content; ?>
</main>

<footer class="text-center text-lg-start" style="background-color: #A5D6A7; color: black;">
    <div class="container p-4">
        <div class="row">
            <!-- Section Contact -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contact</h5>
                <p><i class="bi bi-envelope-fill"></i> ecoride@contact.com</p>
            </div>

            <!-- Section Mentions Légales -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Mentions légales</h5>
                <ul class="list-unstyled">
                    <li><a href="mentions-legales.html" class="text-dark">Voir les mentions légales</a></li>
                </ul>
            </div>

            <!-- Section Réseaux Sociaux -->
            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Suivez-nous</h5>
                <div>
                    <a href="https://facebook.com" class="text-dark me-3" target="_blank"><i class="bi bi-facebook" style="font-size: 1.5rem;"></i></a>
                    <a href="https://twitter.com" class="text-dark me-3" target="_blank"><i class="bi bi-twitter" style="font-size: 1.5rem;"></i></a>
                    <a href="https://instagram.com" class="text-dark me-3" target="_blank"><i class="bi bi-instagram" style="font-size: 1.5rem;"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Copy Right -->
        <div class="text-center p-3" style="background-color: #A5D6A7;">
            <span>© 2024 Ecoride - Tous droits réservés</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
