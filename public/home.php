<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel ='stylesheet' href="./scss/main.css"/>
    <title>Document</title>
</head>
<body>
    
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
            <a class="nav-link" href="/">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/profil">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/recherche">Covoiturage</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/contact">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/signup">Connexion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<main id="main-page">
<?php echo $content;
?>
</main>

<footer class="text-center footer" style="background-color: #A5D6A7;">
    <div class="row">
        <div class="col-6">
            <p> <br/>ecoride@contact.com <br/>
            </p>
        </div>
        <div class="col-6">
            <p><br/>Mentions légales<br/></p>
         </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>