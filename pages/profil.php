<?php
if (!isset($_SESSION['utilisateur_id'])) {
    echo '<div class="error-message">Veuillez vous connecter pour accéder à cette fonctionnalité !</div>';
    exit();
}

require "../auth.php"; 

try {
    $stmt = $PDO->prepare("SELECT pseudo, image_profil, nom, prenom, email, roles, preference_fumeur, preference_animal, preference_personnalisee FROM utilisateur WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        die("Erreur : Utilisateur non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

$stmt = $PDO->prepare("SELECT AVG(note) AS moyenne FROM avis WHERE utilisateur_id_recepteur = :id");
$stmt->execute([':id' => $_SESSION['utilisateur_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$moyenne = ($result && $result['moyenne']) ? round($result['moyenne'], 2) : 0;

$stmt = $PDO->prepare("SELECT modele, couleur, marque, energie, vehicule_date, vehicule_plate, nb_place FROM vehicule WHERE utilisateur_id = :id");
$stmt->execute([':id' => $_SESSION['utilisateur_id']]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>

.sections-container {
    display: flex;
    flex-wrap: wrap; /* Permet aux éléments de passer à la ligne suivante si nécessaire */
    gap: 20px; /* Espace entre les éléments */
    justify-content: center; /* Centrer les éléments dans le conteneur */
    margin-top: 20px;
}

.section-box {
    flex: 1 1 calc(30% - 20px); /* Chaque élément occupe environ 30% de la largeur, moins les marges */
    max-width: 30%;
    min-width: 200px; /* Assure une taille minimale pour les petits écrans */
}

.section-header {
    cursor: pointer;
    padding: 15px 10px;
    background-color: #267aa7;
    color: white;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}

.section-header:hover {
    background-color: #e69500;
}

.section-content {
    display: none;
    background-color: #D9D9D9;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.section-content.open {
    display: block;
}

.btn-modifier-info {
    background-color: #FCB941;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 5px;
}

.btn-modifier-info:hover {
    background-color: #e69500;
}
    .profile-header {
        background-color: #D9D9D9;
        color: black;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    
    .profile-header img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid white;
        margin-bottom: 10px;
    }
    
    .profile-header h3 {
        margin-bottom: 10px;
    }
    
    .section-header  {
        display: flex;
        justify-content: center;
        cursor: pointer;
        padding: 20px 100px;
        background-color: #267aa7;
        color: white;
        border-radius: 5px;
        margin-bottom: 10px;
        text-align: center;
        font-weight: bold;
    }

    
    /* Style pour la note en étoiles */
.star-rating {
    font-size: 20px;
    color: #ccc; /* Gris par défaut */
}

.star-rating .star {
    margin-right: 5px;
}

.star-rating .filled {
    color: gold; /* Couleur des étoiles remplies */
}

    .sections-container{
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    
    .section-header:hover {
        background-color: #e69500;
    }
    
    .section-content {
        display: none;
        background-color: #D9D9D9;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .section-content.open {
        display: block;
    }
    
    .btn-modifier-info {
        background-color: #FCB941;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 5px;
    }
    
    .btn-modifier-info:hover {
        background-color: #e69500;
    }
    #vehicles {
    margin-top: 20px; /* Espace entre le titre et le contenu de la section */
}

.list-group {
    margin-top: 10px; /* Espace entre la liste et le bouton "Ajouter un véhicule" */
    gap: 15px; /* Ajout d'un espacement entre les éléments */
    display: flex;
    flex-direction: column; /* Dispose les éléments verticalement */
}

.list-group-item {
    position: relative; /* Assurez-vous que les éléments contenant les boutons ont une position relative */
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.vehicle-item {
    display: flex;
    flex-direction: column;
    gap: 5px; /* Espace entre les lignes de texte */
    line-height: 1.5; /* Améliore la lisibilité en augmentant l'interligne */
}

.vehicle-icon {
    margin-right: 10px; /* Ajoute un espace entre l'icône et le texte */
}

.btn-modifier-info {
    margin-top: 15px; /* Espace au-dessus du bouton "Ajouter un véhicule" */
    display: block;
    width: 100%; /* Bouton centré et élargi */
    text-align: center;
}

.btn-danger {
    position: absolute;
    top: 5px; /* Ajustez pour un espacement optimal */
    right: 5px; /* Décalez la croix vers le coin supérieur droit */
    border: none;
    background-color: transparent;
    font-size: 1.5rem;
    color: #dc3545;
    cursor: pointer;
}

/* Ajouter un effet au survol de la croix */
.btn-danger:hover {
    color: #a71d2a;
}

    .section-subheader {
    font-size: 1.1em; /* Taille de texte légèrement plus petite que la section principale */
    font-weight: bold;
    margin-top: 20px; /* Espacement entre la section véhicules et la sous-section préférences */
}

.section-content ul {
    list-style-type: none;
    padding-left: 0;
}    
    
</style>

<div class="container mt-5">
    <div class="profile-header">
    <img src="image_profil.php" alt="Photo de Profil">
    <h3 id="pseudo"><?php echo htmlspecialchars($utilisateur['pseudo']); ?></h3>
        <div class="star-rating" id="star-rating">
            <?php
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $moyenne) ? '<span class="star filled">&#9733;</span>' : '<span class="star">&#9734;</span>';
            }
            ?>
        </div>
    </div>

<div class="container mt-5">
    <div class="sections-container">
        <div class="section-box">
            <div class="section-header" onclick="toggleSection('personal-info')">Profil</div>
            <div id="personal-info" class="section-content">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($utilisateur['nom']); ?></p>
                <p><strong>Prénom:</strong> <?php echo htmlspecialchars($utilisateur['prenom']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($utilisateur['email']); ?></p>
                <p><strong>Rôles:</strong> <?php echo htmlspecialchars($utilisateur['roles']); ?></p>
                <button class="btn btn-modifier-info btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">Modifier les informations</button>
            </div>
        </div>

        <div class="section-box">
            <div class="section-header" onclick="toggleSection('vehicles')">Véhicules</div>
            <div id="vehicles" class="section-content">
                <ul class="list-group" id="vehicle-list">
                    <?php
                    if ($vehicules) {
                        foreach ($vehicules as $vehicule) {
                            echo '<li class="list-group-item">';
                            echo '<div class="vehicle-item">';
                            echo '<span><strong>Marque:</strong> ' . htmlspecialchars($vehicule['marque']) . '</span>';
                            echo '<span><strong>Modèle:</strong> ' . htmlspecialchars($vehicule['modele']) . '</span>';
                            echo '<span><strong>Couleur:</strong> ' . htmlspecialchars($vehicule['couleur']) . '</span>';
                            echo '<span><strong>Énergie:</strong> ' . htmlspecialchars($vehicule['energie']) . '</span>';
                            echo '<span><strong>Places:</strong> ' . htmlspecialchars($vehicule['nb_place']) . '</span>';
                            echo '</div>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li>Aucun véhicule trouvé.</li>';
                    }
                    ?>
                </ul>
                <button class="btn-modifier-info" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Ajouter un véhicule</button>
            </div>
        </div>

        <div class="section-box">
            <div class="section-header" onclick="toggleSection('history')">Historique</div>
            <div id="history" class="section-content">
                <p>Aucun trajet récent pour le moment.</p>
            </div>
        </div>
    </div>

    <!-- Ligne 2 : Proposer un trajet -->
    <div class="sections-container">
        <div class="section-box" style="flex: 1 1 100%; max-width: 100%;">
            <div class="section-header" onclick="toggleSection('offer-ride')">Proposer un trajet</div>
            <div id="offer-ride" class="section-content">
                <p>Proposez un trajet à la communauté :</p>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#offerRideModal">Proposer un trajet</button>
            </div>
        </div>
    </div>
</div>


<?php include 'modals.php'; ?>
    <script src="script/profil.js"></script>